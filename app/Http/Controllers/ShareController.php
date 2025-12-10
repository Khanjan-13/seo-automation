<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DocumentShare;
use App\Models\Message;

class ShareController extends Controller
{
    public function view($token)
    {
        // Find the share by token
        $share = DocumentShare::where('share_token', $token)
                              ->where('is_active', true)
                              ->firstOrFail();

        // Get the latest AI message for this chat
        $aiMessage = Message::where('chat_id', $share->chat_id)
                            ->where('sender', 'ai')
                            ->latest()
                            ->first();

        $content = $aiMessage ? $aiMessage->content : 'No content available.';
        $chat = $share->chat;

        return view('share.view', compact('content', 'chat', 'token'));
    }
}
