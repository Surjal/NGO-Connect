<?php

namespace App\Http\Controllers\Ngo;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventMilestone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MilestoneController extends Controller
{
    public function store(Request $request, $eventId)
    {
        $event = Event::findOrFail($eventId);
        
        if ($event->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $milestone = $event->milestones()->create([
            'title' => $request->title,
            'description' => $request->description,
            'status' => 'pending',
            'order' => $event->milestones()->count() + 1,
        ]);

        return back()->with('success', 'Milestone added successfully.');
    }

    public function updateStatus(Request $request, $milestoneId)
    {
        $milestone = EventMilestone::findOrFail($milestoneId);
        
        if ($milestone->event->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'status' => 'required|in:pending,in_progress,completed',
        ]);

        $milestone->update(['status' => $request->status]);

        return back()->with('success', 'Milestone status updated.');
    }

    public function destroy($milestoneId)
    {
        $milestone = EventMilestone::findOrFail($milestoneId);
        
        if ($milestone->event->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $milestone->delete();

        return back()->with('success', 'Milestone deleted.');
    }
}
