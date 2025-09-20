<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Message;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Helpers\ApiResponse;

class MessageController extends Controller
{
    // Send a new message
    public function send(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'content' => 'required|string',
        ]);

        $message = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->input('receiver_id'),
            'content' => $request->input('content'),
        ]);

        return ApiResponse::success('Message sent successfully', $message);
    }

    // Fetch conversation between logged-in user and another user
    public function conversation($userId)
    {
        // Validate user exists
        $user = User::findOrFail($userId);

        $messages = Message::where(function ($q) use ($userId) {
                $q->where('sender_id', Auth::id())
                  ->where('receiver_id', $userId);
            })
            ->orWhere(function ($q) use ($userId) {
                $q->where('sender_id', $userId)
                  ->where('receiver_id', Auth::id());
            })
            ->with('sender', 'receiver')
            ->orderBy('created_at', 'asc')
            ->get();

        return ApiResponse::success('Conversation fetched successfully', $messages);
    }

    // Mark messages from a user as read
    public function markAsRead($userId)
    {
        // Validate user exists
        $user = User::findOrFail($userId);

        Message::where('receiver_id', Auth::id())
            ->where('sender_id', $userId)
            ->update(['is_read' => true]);

        return ApiResponse::success('Messages marked as read');
    }

    public function conversations()
{
    $userId = Auth::id();

    $conversations = Message::where('sender_id', $userId)
        ->orWhere('receiver_id', $userId)
        ->orderBy('created_at', 'desc')
        ->get()
        ->groupBy(function($msg) use ($userId) {
            // Group by the other user's ID
            return $msg->sender_id === $userId ? $msg->receiver_id : $msg->sender_id;
        })
        ->map(function($messages, $otherUserId) {
            $lastMessage = $messages->last();
            $unreadCount = $messages->where('receiver_id', Auth::id())->where('is_read', false)->count();
            $otherUser = User::find($otherUserId);
            return [
                'user' => [
                    'id' => $otherUser->id,
                    'name' => $otherUser->name,
                    'email' => $otherUser->email,
                    'role' => $otherUser->role,
                ],
                'last_message' => $lastMessage->content,
                'unread_count' => $unreadCount,
                'last_message_time' => $lastMessage->created_at,
            ];
        })->values();

    return ApiResponse::success('Conversations fetched successfully', $conversations);
}

}
