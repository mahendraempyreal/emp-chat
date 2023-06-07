<?php
use App\ChatApp\ChatMessenger;
$chatData = new ChatMessenger();

$seenIcon = (!!$seen ? 'check-double' : 'check');
/* $timeAndSeen = "<span data-time='$created_at' class='message-time'>
    ".($isSender ? "<span class='fas fa-$seenIcon' seen'></span>" : '' )." <span class='time'>$timeAgo</span>
</span>"; */
$timeAndSeen = "<div class='text-muted small text-nowrap mt-2'>".($isSender ? "<span class='fas fa-$seenIcon' seen'></span>" : '' )."$timeAgo</div>";
?>
<div class="@if($isSender) chat-message-right @else chat-message-left @endif pb-4" data-id="{{ $id }}">
    <div>
        <img src="https://bootdey.com/img/Content/avatar/avatar3.png" class="rounded-circle mr-1" alt="Sharon Lessman" width="40" height="40">
        <!-- {{ $timeAndSeen }} -->
    </div>
    <div class="flex-shrink-1 bg-light rounded py-2 px-3 ml-3">
        @if (@$attachment->type != 'image' || $message)
            {!! ($message == null && $attachment != null && @$attachment->type != 'file') ? $attachment->title : nl2br($message) !!}
            {!! $timeAndSeen !!}
            {{-- If attachment is a file --}}
            @if(@$attachment->type == 'file')
            <a href="{{ route(config('empchat.attachments.download_route_name'), ['fileName'=>$attachment->file]) }}" class="file-download">
                <span class="fas fa-file"></span> {{$attachment->title}}</a>
            @endif
        @endif
        @if(@$attachment->type == 'image')
        <div class="image-wrapper" style="text-align: {{$isSender ? 'end' : 'start'}}">
            <div class="image-file chat-image" style="background-image: url('{{ $chatData->getAttachmentUrl($attachment->file) }}')">
                <div>{{ $attachment->title }}</div>
            </div>
            <div style="margin-bottom:5px">
                {!! $timeAndSeen !!}
            </div>
        </div>
        @endif

        <!-- <div class="font-weight-bold mb-1">Sharon Lessman</div> -->
        <!-- Sit meis deleniti eu, pri vidit meliore docendi ut, an eum erat animal commodo. -->
    </div>
</div>
