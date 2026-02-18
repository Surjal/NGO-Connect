<?php

namespace App\Http\Controllers\Ngo;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Notifications\VolunteerVerified;

class VolunteerController extends Controller
{
    public function volunteers(Request $request)
    {
        $query = Event::where('user_id', Auth::id());

        // Filter by Event Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('title', 'like', "%{$search}%");
        }

        // Enable Query Log
        \DB::enableQueryLog();

        $events = $query->with(['volunteers' => function($q) use ($request) {
            // Bypass any global scopes (like soft deletes or verified checks) that might hide users
            $q->withoutGlobalScopes();
            
            // Optional: Filter individual volunteers if needed
            if ($request->filled('status')) {
                $q->wherePivot('status', $request->status);
            }
        }])->latest()->get();

        // Log the query and result
        \Log::info("Volunteer Query executed.");
        \Log::info(\DB::getQueryLog());
        
        foreach($events as $event) {
             \Log::info("Event ID: {$event->id} - Volunteers Count: " . $event->volunteers->count());
             foreach($event->volunteers as $v) {
                 \Log::info("  - Vol: {$v->id} ({$v->name}) Status: {$v->pivot->status}");
             }
        }

        return view('ngo.volunteers.index', compact('events'));
    }

    public function verifyVolunteer(Request $request, $eventId, $userId)
    {
        $event = Event::findOrFail($eventId);
        $volunteer = User::findOrFail($userId);

        if ($event->user_id !== Auth::id()) {
            return redirect()->route('ngo.volunteers')->with('error', 'Unauthorized action.');
        }

        // Ensure user is actually registered for this event
        if (!$event->volunteers()->where('user_id', $userId)->exists()) {
            return redirect()->route('ngo.volunteers')->with('error', 'User is not registered for this event.');
        }

        // Update participation status
        $event->volunteers()->updateExistingPivot($userId, ['status' => 'accepted']);

        // Issue verified certificate (Digital recognition foundation)
        $certificateHash = bin2hex(random_bytes(16));
        \App\Models\Certificate::firstOrCreate(
            ['user_id' => $userId, 'event_id' => $eventId],
            [
                'certificate_hash' => $certificateHash,
                'issued_at' => now(),
            ]
        );

        // Send Email Notification to the volunteer
        try {
            $volunteer->notify(new VolunteerVerified($event));
        } catch (\Exception $e) {
            \Log::error("Failed to send volunteer verification email: " . $e->getMessage());
            // We continue as the database update was successful
        }

        return redirect()->route('ngo.volunteers')->with('success', 'Volunteer verified, certificate issued, and notification sent!');
    }
}
