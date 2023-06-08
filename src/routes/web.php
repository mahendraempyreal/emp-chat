<?php

use Illuminate\Support\Facades\Route;

// Route::get('/login','ChatController@login')->name('login');

/*
* This is the main app route [Chat Messenger]
*/

Route::get('/', 'ChatController@index')->name(config('eichat.routes.prefix'));

/**
 *  Fetch info for specific id [user/group]
 */
Route::post('/idInfo', 'ChatController@idFetchData');

/**
 * Send message route
 */
// Route::post('/sendMessage', 'ChatController@send')->name('send.message');
Route::post('/sendMessage', 'ChatController@sendMessage')->name('send.message');

/**
 * Fetch messages
 */
Route::post('/fetchMessages', 'ChatController@fetchMessageByUser')->name('fetch.messages');

/**
 * Download attachments route to create a downloadable links
 */
Route::get('/download/{fileName}', 'ChatController@download')->name(config('eichat.attachments.download_route_name'));

/**
 * Authentication for pusher private channels
 */
// Route::post('/chat/auth', 'ChatController@pusherAuth')->name('pusher.auth');

/**
 * Make messages as seen
 */
Route::post('/makeSeen', 'ChatController@markAsSeen')->name('messages.seen');

/**
 * Get contacts
 */
Route::get('/getContacts', 'ChatController@getContacts')->name('contacts.get');

/**
 * Update contact item data
 */
Route::post('/updateContacts', 'ChatController@updateContactItem')->name('contacts.update');

/**
 * Search in messenger
 */
Route::get('/search', 'ChatController@search')->name('search');

/**
 * Get shared photos
 */
Route::post('/shared', 'ChatController@sharedPhotos')->name('shared');

/**
 * Delete Conversation
 */
Route::post('/deleteConversation', 'ChatController@deleteConversation')->name('conversation.delete');

/**
 * Delete Message
 */
Route::post('/deleteMessage', 'ChatController@deleteMessage')->name('message.delete');

/**
 * Set active status
 */
Route::post('/setActiveStatus', 'ChatController@setActiveStatus')->name('activeStatus.set');

/*
* user view by id.
* Note : If you added routes after the [User] which is the below one,
* it will considered as user id.
*
* e.g. - The commented routes below :
*/
// Route::get('/route', function(){ return 'Munaf'; }); // works as a route
Route::get('/{id}', 'ChatController@index')->name('user');
// Route::get('/route', function(){ return 'Munaf'; }); // works as a user id
