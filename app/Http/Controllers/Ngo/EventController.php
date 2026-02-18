<?php

namespace App\Http\Controllers\Ngo;

use App\Models\Event;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Prompts\Concerns\Events;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    public function events(Request $request)
    {
        $query = Event::where('user_id', Auth::user()->id);

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
            }
        }

        $events = $query->latest()->paginate(10)->withQueryString();
        
        return view('ngo.events.index', compact('events'));
    }

    public function showEventDetails($id)
    {
        $event = Event::findOrFail($id);
        $currentDate = now();
        if ($event->end_date && $event->end_date < $currentDate) {
            $event['timing'] = 'old';
        }
        return view('ngo.events.details', compact('event'));
    }

    public function createEvent()
    {
        return view('ngo.events.create');
    }

    public function storeEvent(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'requirements' => 'required|string',
            'location' => 'required|string|max:255',
            'type' => 'required|in:0,1',
            'start_date' => 'required|date|after:now',
            'end_date' => 'required|date|after:start_date',
            'cover_image_path_name' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'capacity' => 'required|string|max:255',
            'is_volunteers_required' => 'required|boolean',
        ]);

        $event = new Event($request->only([
            'title',
            'description',
            'requirements',
            'location',
            'type',
            'start_date',
            'end_date',
            'capacity',
            'is_volunteers_required',
        ]));
        $event->user_id = Auth::user()->id;

        if ($request->hasFile('cover_image_path_name')) {
            $path = $request->file('cover_image_path_name')->store('event_images', 'public');
            $event->cover_image_path_name = $path;
        }

        $event->save();

        return redirect()->route('ngo.events')->with('success', 'Event created successfully.');
    }

    public function editEventDetails($id)
    {
        $event = Event::find($id);
        return view('ngo.events.editDetails', compact('event'));
    }

    public function updateEventDetails(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'requirements' => 'nullable|string',
            'location' => 'required|string|max:255',
            'type' => 'required|in:0,1',
            'start_date' => 'required|date|after:now',
            'end_date' => 'required|date|after:start_date',
            'cover_image_path_name' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'capacity' => 'required|string|max:255',
            'is_volunteers_required' => 'required|boolean',
        ]);

        // Handle image upload
        if ($request->hasFile('cover_image_path_name')) {
            // Store in 'storage/app/public/event_images'
            $path = $request->file('cover_image_path_name')->store('event_images', 'public');
            $validated['cover_image_path_name'] = $path;
        }

        // Update event record
        $event->update($validated);

        return redirect()
            ->route('ngo.events', $event->id)
            ->with('success', 'Event updated successfully!');
    }

    public function deleteEvent($id)
    {
        // Find the event by ID
        $event = Event::find($id);

        if (!$event) {
            return redirect()->route('ngo.events')->with('failure', 'Error deleting event!');
        }

        // Delete the event
        $event->delete();

        return redirect()->route('ngo.events')->with('success', 'Event deleted successfully!');
    }
}
