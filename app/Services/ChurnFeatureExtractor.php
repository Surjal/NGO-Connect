<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\CircleReply;
use App\Models\Event;
use App\Models\Message;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Carbon;
use InvalidArgumentException;

class ChurnFeatureExtractor
{
    public function extractFeatures(int $volunteerId, int $ngoId): array
    {
        $volunteer = User::findOrFail($volunteerId);
        $ngo = User::findOrFail($ngoId);

        if (!$volunteer->isPeople()) {
            throw new InvalidArgumentException('Volunteer must be a people user.');
        }

        if (!$ngo->isNgo()) {
            throw new InvalidArgumentException('NGO must be an NGO user.');
        }

        $cutoff = Carbon::now()->subDays(30);

        $ngoEvents = Event::where('user_id', $ngoId)
            ->with([
                'volunteers' => function ($query) use ($volunteerId) {
                    $query->where('users.id', $volunteerId);
                },
            ])
            ->get(['id', 'user_id']);

        $registeredEventIds = $ngoEvents
            ->filter(fn (Event $event) => $event->volunteers->isNotEmpty())
            ->pluck('id')
            ->values();

        $totalEventsRegistered = (float) $registeredEventIds->count();

        $attendances = Attendance::where('user_id', $volunteerId)
            ->whereIn('event_id', $registeredEventIds)
            ->get(['id', 'event_id', 'checked_in_at', 'created_at']);

        $totalEventsAttended = (float) $attendances
            ->filter(fn (Attendance $attendance) => $attendance->checked_in_at !== null)
            ->count();

        $attendanceRate = $totalEventsRegistered > 0.0
            ? (float) ($totalEventsAttended / $totalEventsRegistered)
            : 0.0;

        $latestAttendance = $attendances
            ->filter(fn (Attendance $attendance) => $attendance->checked_in_at !== null)
            ->sortByDesc('checked_in_at')
            ->first();

        $latestRegistration = $ngoEvents
            ->flatMap(function (Event $event) {
                return $event->volunteers->map(fn (User $volunteer) => $volunteer->pivot?->created_at);
            })
            ->filter()
            ->sortDesc()
            ->first();

        $referenceDate = $latestAttendance?->checked_in_at
            ?? $latestRegistration
            ?? Carbon::now();

        $daysSinceLastAttendance = (float) Carbon::parse($referenceDate)->diffInDays(Carbon::now());

        $ngoPosts = Post::where('user_id', $ngoId)
            ->withCount([
                'likes as volunteer_likes_count' => function ($query) use ($volunteerId, $cutoff) {
                    $query->where('user_id', $volunteerId)
                        ->where('created_at', '>=', $cutoff);
                },
                'comments as volunteer_comments_count' => function ($query) use ($volunteerId, $cutoff) {
                    $query->where('user_id', $volunteerId)
                        ->where('created_at', '>=', $cutoff);
                },
            ])
            ->get(['id', 'user_id']);

        $postsLikedLast30Days = (float) $ngoPosts->sum('volunteer_likes_count');
        $commentsMadeLast30Days = (float) $ngoPosts->sum('volunteer_comments_count');

        $circleRepliesLast30Days = (float) CircleReply::where('user_id', $volunteerId)
            ->where('created_at', '>=', $cutoff)
            ->whereHas('thread', function ($query) use ($ngoId) {
                $query->where('ngo_id', $ngoId);
            })
            ->count();

        $messagesSentLast30Days = (float) Message::where('sender_id', $volunteerId)
            ->where('receiver_id', $ngoId)
            ->where('created_at', '>=', $cutoff)
            ->count();

        $badgesEarnedTotal = (float) $volunteer->badges()->count();

        return [
            'days_since_last_attendance' => $daysSinceLastAttendance,
            'total_events_attended' => $totalEventsAttended,
            'attendance_rate' => (float) $attendanceRate,
            'total_events_registered' => $totalEventsRegistered,
            'posts_liked_last_30_days' => $postsLikedLast30Days,
            'comments_made_last_30_days' => $commentsMadeLast30Days,
            'circle_replies_last_30_days' => $circleRepliesLast30Days,
            'messages_sent_last_30_days' => $messagesSentLast30Days,
            'badges_earned_total' => $badgesEarnedTotal,
        ];
    }
}
