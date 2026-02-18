<?php

namespace App\Http\Controllers\People;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    /**
     * Handle QR Check-in for an event.
     */
    public function checkIn($token)
    {
        $user = Auth::user();
        
        // Find the event by its unique check-in token
        $event = Event::where('check_in_token', $token)->firstOrFail();

        // 1. Verify user is a volunteer for this event
        $volunteerRecord = $event->volunteers()->where('user_id', $user->id)->first();

        if (!$volunteerRecord) {
            return redirect()->route('common.feed')->with('error', 'You are not registered as a volunteer for this event.');
        }

        // 2. Verify volunteer application was accepted
        if ($volunteerRecord->pivot->status !== 'accepted') {
            return redirect()->route('people.volunteer.details', $event->id)
                             ->with('error', 'Your volunteer application for this event has not been accepted yet.');
        }

        // 3. Perform check-in
        Attendance::updateOrCreate(
            ['user_id' => $user->id, 'event_id' => $event->id],
            [
                'status' => 'checked_in',
                'checked_in_at' => now(),
            ]
        );

        return redirect()->route('people.profile')
                         ->with('success', 'Verified check-in successful for "' . $event->title . '"! Your impact has been recorded.');
    }
}
