<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessagingController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        
        // Get all unique users this user has messaged with
        $conversations = Message::where('sender_id', $userId)
            ->orWhere('receiver_id', $userId)
            ->with(['sender', 'receiver'])
            ->latest()
            ->get()
            ->groupBy(function ($message) use ($userId) {
                return $message->sender_id == $userId ? $message->receiver_id : $message->sender_id;
            });

        return view('common.messaging.index', compact('conversations'));
    }

    public function show($otherUserId)
    {
        $userId = Auth::id();
        $otherUser = User::findOrFail($otherUserId);

        $messages = Message::where(function ($q) use ($userId, $otherUserId) {
                $q->where('sender_id', $userId)->where('receiver_id', $otherUserId);
            })->orWhere(function ($q) use ($userId, $otherUserId) {
                $q->where('sender_id', $otherUserId)->where('receiver_id', $userId);
            })
            ->oldest()
            ->get();

        // Mark as read
        Message::where('sender_id', $otherUserId)
            ->where('receiver_id', $userId)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        if (request()->ajax()) {
            return response()->json([
                'messages' => $messages,
                'otherUser' => $otherUser
            ]);
        }

        // For non-AJAX, we still return the view but we might need the list of all conversations too
        $conversations = $this->getConversations($userId);

        return view('common.messaging.show', compact('messages', 'otherUser', 'conversations'));
    }

    public function getMessages($otherUserId)
    {
        $userId = Auth::id();
        $lastMessageId = request()->query('last_id', 0);

        $newMessages = Message::where(function ($q) use ($userId, $otherUserId) {
                $q->where('sender_id', $otherUserId)->where('receiver_id', $userId);
            })
            ->where('id', '>', $lastMessageId)
            ->oldest()
            ->get();

        // Mark as read
        if ($newMessages->count() > 0) {
            Message::where('sender_id', $otherUserId)
                ->where('receiver_id', $userId)
                ->whereNull('read_at')
                ->update(['read_at' => now()]);
        }

        return response()->json([
            'messages' => $newMessages
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'content' => 'required|string|max:1000',
        ]);

        $message = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'content' => $request->content,
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        }

        return back()->with('success', 'Message sent.');
    }

    private function getConversations($userId)
    {
        return Message::where('sender_id', $userId)
            ->orWhere('receiver_id', $userId)
            ->with(['sender', 'receiver'])
            ->latest()
            ->get()
            ->groupBy(function ($message) use ($userId) {
                return $message->sender_id == $userId ? $message->receiver_id : $message->sender_id;
            });
    }
}
