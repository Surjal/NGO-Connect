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
        \Log::info('Milestone Store Method Called', [
            'eventId' => $eventId,
            'request_data' => $request->all(),
            'user_id' => Auth::id()
        ]);

        $event = Event::findOrFail($eventId);
        
        if ($event->user_id !== Auth::id()) {
            \Log::warning('Unauthorized milestone creation attempt', [
                'event_user_id' => $event->user_id,
                'auth_user_id' => Auth::id()
            ]);
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        \Log::info('Validation passed', ['validated' => $validated]);

        $milestone = $event->milestones()->create([
            'title' => $request->title,
            'description' => $request->description,
            'status' => 'pending',
            'order' => $event->milestones()->count() + 1,
        ]);

        \Log::info('Milestone created successfully', ['milestone_id' => $milestone->id]);

        return redirect()->route('ngo.event.details', $event->id)->with('success', 'Milestone added successfully.');
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
