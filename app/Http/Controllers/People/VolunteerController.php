<?php

namespace App\Http\Controllers\People;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventHasVolunteer;
use App\Models\User;
use App\Notifications\VolunteerRegistered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VolunteerController extends Controller
{
    //
    public function index(Request $request)
    {
        $userId = Auth::id();
        $query = Event::where('is_volunteers_required', true)
            ->withExists(['volunteers as is_registered' => function ($query) use ($userId) {
                $query->where('user_id', $userId);
            }]);

        // Search Keyword
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        // Filter by Type (Online/Offline)
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by Status
        if ($request->filled('status')) {
            $now = now();
            switch ($request->status) {
                case 'upcoming':
                    $query->where('start_date', '>', $now);
                    break;
                case 'live':
                    $query->where('start_date', '<=', $now)
                          ->where('end_date', '>=', $now);
                    break;
                case 'completed':
                    $query->where('end_date', '<', $now);
                    break;
                default:
                    // Default to show upcoming and live if no status filter
                    $query->where('end_date', '>=', $now);
            }
        } else {
            // Default behavior: show only ongoing or future events
            $query->where('end_date', '>=', now());
        }

        $events = $query->latest()->get();

        return view('people.volunteer.show', compact('events'));
    }

    public function apply(Request $request)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
        ]);

        $event = Event::findOrFail($request->event_id);
        $user = Auth::user();

        // Ensure user is authenticated and not the event organizer
        if (!$user || $user->id === $event->user_id) {
            return response()->json(['message' => 'Unauthorized or cannot register for own event'], 401);
        }

        // Check if volunteers are required
        if (!$event->is_volunteers_required) {
            return response()->json(['message' => 'Volunteers are not required for this event'], 400);
        }

        // Check event capacity
        $registeredCount = $event->volunteers()->where('status', 'accepted')->count();
        if ($registeredCount >= $event->capacity) {
            return response()->json(['message' => 'Event capacity reached'], 400);
        }

        // Check if user is already registered
        if ($event->volunteers()->where('user_id', $user->id)->exists()) {
            return response()->json(['message' => 'Already registered for this event'], 400);
        }

        // Register volunteer with pending status
        $event->volunteers()->attach($user->id, ['status' => 'pending']);

        // Notify the NGO owner - wrapped in try-catch to prevent mail errors from breaking registration
        try {
            $ngoUser = $event->user;
            $ngoUser->notify(new VolunteerRegistered($event, $user));
        } catch (\Exception $e) {
            \Log::error("Failed to send volunteer notification: " . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Registration submitted successfully',
            'status' => 'pending',
        ], 201);
    }

    public function showEventDetails($id)
    {
        $userId = Auth::id();
        $event = Event::where('id', $id)
            ->withExists(['volunteers as is_registered' => function ($query) use ($userId) {
                $query->where('user_id', $userId);
            }])
            ->firstOrFail();
        // Get volunteer status if registered
        $volunteerStatus = null;
        if ($event->is_registered) {
            $volunteer = $event->volunteers()->where('user_id', $userId)->first();
            $volunteerStatus = $volunteer ? $volunteer->pivot->status : null;
        }

        $currentDate = now();
        if ($event->end_date < $currentDate) {
            $event['timing'] = 'old';
        }
        return view('people.events.details', compact('event', 'volunteerStatus'));
    }
}
