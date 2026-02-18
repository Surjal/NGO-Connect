<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Models\CircleThread;
use App\Models\CircleReply;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CircleController extends Controller
{
    public function index($ngoId)
    {
        $ngo = User::where('role_id', 1)->findOrFail($ngoId);
        $threads = $ngo->threadsAsNgo()->with(['user', 'replies'])->latest()->get();

        return view('common.circles.index', compact('ngo', 'threads'));
    }

    public function show($threadId)
    {
        $thread = CircleThread::with(['ngo', 'user', 'replies.user'])->findOrFail($threadId);
        return view('common.circles.show', compact('thread'));
    }

    public function storeThread(Request $request, $ngoId)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        CircleThread::create([
            'ngo_id' => $ngoId,
            'user_id' => Auth::id(),
            'title' => $request->title,
            'content' => $request->content,
        ]);

        return back()->with('success', 'Circle thread started.');
    }

    public function storeReply(Request $request, $threadId)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        CircleReply::create([
            'thread_id' => $threadId,
            'user_id' => Auth::id(),
            'content' => $request->content,
        ]);

        return back()->with('success', 'Reply posted.');
    }
}
