<?php

namespace Mahendraempyreal\EmpChat\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Mahendraempyreal\EmpChat\ChatMessenger as ChatData;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Mahendraempyreal\EmpChat\Models\ChatMessage as Message;
use App\Models\User;
use Validator;
use Session;

class ChatController extends Controller{

  protected $perPage = 30;
  
  protected $chatData;

	public function __construct(){
    $this->chatData = new ChatData();
  }

  public function index(){
    return view('empchat::chat.app');
  }

  public function login(){
    Auth::loginUsingId($_GET['id'] ?? 1);
    return redirect()->back();
  }

  public function sendMessage(Request $request){
    try {
      //dd($request->all());
      $error = (object)[
        'status' => 0,
        'message' => null
      ];

      $attachment = null;
      $attachment_title = null;

      // if there is attachment [file]
      if ($request->hasFile('file')) {
        // allowed extensions
        $allowed_images = $this->chatData->getAllowedImages();
        $allowed_files  = $this->chatData->getAllowedFiles();
        $allowed        = array_merge($allowed_images, $allowed_files);

        $file = $request->file('file');
        // check file size
        if ($file->getSize() < $this->chatData->getMaxUploadSize()) {
            if (in_array(strtolower($file->extension()), $allowed)) {
                // get attachment name
                $attachment_title = $file->getClientOriginalName();
                // upload attachment and store the new name
                $attachment = Str::uuid() . "." . $file->extension();
                $file->storeAs(config('eichat.attachments.folder'), $attachment, config('eichat.storage_disk_name'));
            } else {
                $error->status = 1;
                $error->message = "File extension not allowed!";
            }
        } else {
            $error->status = 1;
            $error->message = "File size you are trying to upload is too large!";
        }
      }
      if (!$error->status) {
        $message = $this->chatData->newMessage([
          'from_id' => Auth::user()->id,
          'to_id' => $request['id'],
          'body' => htmlentities(trim($request['message']), ENT_QUOTES, 'UTF-8'),
          'attachment' => ($attachment) ? json_encode((object)[
              'new_name' => $attachment,
              'old_name' => htmlentities(trim($attachment_title), ENT_QUOTES, 'UTF-8'),
          ]) : null,
        ]);
        $messageData = $this->chatData->parseMessage($message);
        if (Auth::user()->id != $request['id']) {
          //send message event
          /* [
            'from_id' => Auth::user()->id,
            'to_id' => $request['id'],
            'message' => $this->chatData->messageCard($messageData, true)
          ] */
        }
        return Response::json([
          'status' => '200',
          'error' => $error,
          'message' => $this->chatData->messageCard(@$messageData),
          'receiver_message' => $this->chatData->messageCard(@$messageData, true),
          'tempID' => $request['temporaryMsgId'],
        ]);
      }
    } catch (\Exception $e){
      $error->status = 1;
      $error->message = $e->getMessage();
      return Response::json([
          'status' => '400',
          'error' => $error,
          'message' => $e->getMessage(),
          'tempID' => $request['temporaryMsgId'],
      ]);
    }
  }
  public function fetchMessageByUser(Request $request){
    $query = $this->chatData->fetchMessagesQuery($request['id'])->latest();
    $messages = $query->paginate($request->per_page ?? $this->perPage);
    $totalMessages = $messages->total();
    $lastPage = $messages->lastPage();
    $response = [
        'total' => $totalMessages,
        'last_page' => $lastPage,
        'last_message_id' => collect($messages->items())->last()->id ?? null,
        'messages' => '',
    ];
    // dd($messages->toArray());
    // if there is no messages yet.
    if ($totalMessages < 1) {
        $response['messages'] ='<p class="message-hint center-el"><span>Say \'hi\' and start messaging</span></p>';
        return Response::json($response);
    }
    if (count($messages->items()) < 1) {
        $response['messages'] = '';
        return Response::json($response);
    }
    $allMessages = null;
    foreach ($messages->reverse() as $message) {
        $allMessages .= $this->chatData->messageCard(
            $this->chatData->parseMessage($message)
        );
    }
    $response['messages'] = $allMessages;
    return Response::json($response);
  }
  /**
   * Make messages as seen
   *
   * @param Request $request
   * @return JsonResponse|void
   */
  public function markAsSeen(Request $request){
    // make as seen
    $seen = $this->chatData->makeSeen($request['id']);
    // send the response
    return Response::json([
        'status' => $seen,
    ], 200);
  }
  /**
   * Get user list that are not in chat list
   *
   * @param Request $request
   * @return JsonResponse
   */
  
  public function getUserNotInChatList(Request $request){
    /* $usersList = User::where(function($q) {
      $q->whereNotIn('id', function($query){
        $query->select('from_id')
        ->from('ei_chat_message')
        ->where('from_id','!=',Auth::user()->id)
        ->orWhere('to_id','!=',Auth::user()->id);
      })->whereNotIn('id', function($query){
        $query->select('to_id')
        ->from('ei_chat_message')
        ->where('from_id','!=',Auth::user()->id)
        ->orWhere('to_id','!=',Auth::user()->id);
      });
    })->get(); */
    $usersList = User::where('id','!=',Auth::user()->id)->where(function($q) {
      $q->whereNotIn('id', function($query){
        $query->select(DB::raw('distinct (case when to_id = '.Auth::user()->id.' then from_id else to_id end) as contact_id'))
        ->from('ei_chat_message')
        ->where('from_id','=',Auth::user()->id)
        ->orWhere('to_id','=',Auth::user()->id);
      });
    })->get();
  
    $contacts = '';
    if (count($usersList) > 0) {
      $contacts = '<option value="">Select User</option>';
      foreach ($usersList as $user) {
        $contacts .= '<option value="'.$user->id.'">'.$user->name.'</option>';
      }
    } else {
      $contacts = '<option>Select User</option>';
    }

    return Response::json([
      'usersList' => $usersList,
      'contacts' => $contacts,
    ], 200);
  }

  /**
   * Get contact card html when start a new chat
   *
   * @param Request $request id
   * @return JsonResponse
   */
  public function getContactCard(Request $request){
    $user = User::find($request->id);
    $lastMessage = (object)['from_id' => '','to_id' => '', 'body'=>'', 'created_at'=>'', 'timeAgo'=>'', 'attachment'=>null];
    // Get Unseen messages counter
    $unseenCounter = isset($request->unseen) ? 1 : 0;
    $html = view('empchat::chat.layouts.listItem', [
        'get' => 'users',
        'user' => $this->chatData->getUserWithAvatar($user),
        'lastMessage' => $lastMessage,
        'unseenCounter' => $unseenCounter,
        ])->render();
    return Response::json([
          'html' => $html,
        ], 200);    
  }
  /**
   * Get contacts list
   *
   * @param Request $request
   * @return JsonResponse
   */
  public function getContacts(Request $request){
    // get all users that received/sent message from/to [Auth user]
    $users = Message::join('users',  function ($join) {
        $join->on('ei_chat_message.from_id', '=', 'users.id')
            ->orOn('ei_chat_message.to_id', '=', 'users.id');
    })
    ->where(function ($q) {
        $q->where('ei_chat_message.from_id', Auth::user()->id)
        ->orWhere('ei_chat_message.to_id', Auth::user()->id);
    })
    ->where('users.id','!=',Auth::user()->id)
    ->select('users.*',DB::raw('MAX(ei_chat_message.created_at) max_created_at'))
    ->orderBy('max_created_at', 'desc')
    ->groupBy('users.id')
    ->paginate($request->per_page ?? $this->perPage);

    $usersList = $users->items();

    if (count($usersList) > 0) {
      $contacts = '';
      foreach ($usersList as $user) {
          $contacts .= $this->chatData->getContactItem($user);
      }
    } else {
      $contacts = '<p class="message-hint center-el"><span>Your contact list is empty</span></p>';
    }

    return Response::json([
      'contacts' => $contacts,
      'total' => $users->total() ?? 0,
      'last_page' => $users->lastPage() ?? 1,
    ], 200);
  }

  /**
   * Search contacts
   *
   * @param Request $request
   * @return JsonResponse|void
   */
  public function search(Request $request){
    $getRecords = null;
    $input = trim(filter_var($request['input']));
    $records = User::where('id','!=',Auth::user()->id)
                ->where('name', 'LIKE', "%{$input}%")
                ->paginate($request->per_page ?? $this->perPage);
    foreach ($records->items() as $record) {
        $getRecords .= view('empchat::layouts.listItem', [
            'get' => 'search_item',
            'user' => $this->chatData->getUserWithAvatar($record),
        ])->render();
    }
    if($records->total() < 1){
        $getRecords = '<p class="message-hint center-el"><span>Nothing to show.</span></p>';
    }
    // send the response
    return Response::json([
      'records' => $getRecords,
      'total' => $records->total(),
      'last_page' => $records->lastPage()
    ], 200);
  }

  /**
   * Get shared photos
   *
   * @param Request $request
   * @return JsonResponse|void
   */
  public function sharedPhotos(Request $request){
    $shared = $this->chatData->getSharedPhotos($request['user_id']);
    $sharedPhotos = null;

    // shared with its template
    for ($i = 0; $i < count($shared); $i++) {
        $sharedPhotos .= view('empchat::layouts.listItem', [
            'get' => 'sharedPhoto',
            'image' => Chatify::getAttachmentUrl($shared[$i]),
        ])->render();
    }
    // send the response
    return Response::json([
        'shared' => count($shared) > 0 ? $sharedPhotos : '<p class="message-hint"><span>Nothing shared yet</span></p>',
    ], 200);
  }
  /**
   * Delete conversation
   *
   * @param Request $request
   * @return JsonResponse
   */
  public function deleteConversation(Request $request){
    // delete
    $delete = $this->chatData->deleteConversation($request['id']);

    // send the response
    return Response::json([
        'deleted' => $delete ? 1 : 0,
    ], 200);
  }

  /**
   * Delete message
   *
   * @param Request $request
   * @return JsonResponse
   */
  public function deleteMessage(Request $request){
    // delete
    $delete = $this->chatData->deleteMessage($request['id']);

    // send the response
    return Response::json([
        'deleted' => $delete ? 1 : 0,
    ], 200);
  }
  /**
   * Set user's actloginive status
   *
   * @param Request $request
   * @return JsonResponse
   */
  public function setActiveStatus(Request $request){
    $activeStatus = $request['status'] > 0 ? 1 : 0;
    $status = User::where('id', Auth::user()->id)->update(['login_status' => $activeStatus]);
    return Response::json([
        'status' => $status,
    ], 200);
  }
}
