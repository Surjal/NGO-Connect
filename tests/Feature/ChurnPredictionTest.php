<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\Badge;
use App\Models\ChurnPrediction;
use App\Models\CircleReply;
use App\Models\CircleThread;
use App\Models\Event;
use App\Models\Message;
use App\Models\Ngo;
use App\Models\Post;
use App\Models\PostHasComments;
use App\Models\PostHasLikes;
use App\Models\User;
use App\Notifications\VolunteerChurnAlert;
use App\Services\ChurnFeatureExtractor;
use App\Services\ChurnPredictionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ChurnPredictionTest extends TestCase
{
    use RefreshDatabase;

    public function test_feature_extractor_returns_correct_keys(): void
    {
        [$ngoUser, $volunteer] = $this->createNgoAndVolunteerPair();
        $event = $this->createEventForNgo($ngoUser);
        $event->volunteers()->attach($volunteer->id, [
            'status' => 'accepted',
            'created_at' => now()->subDays(12),
            'updated_at' => now()->subDays(12),
        ]);

        Attendance::create([
            'user_id' => $volunteer->id,
            'event_id' => $event->id,
            'status' => 'checked_in',
            'checked_in_at' => now()->subDays(7),
        ]);

        $post = Post::create([
            'description' => 'Thanks for supporting our cause.',
            'type' => 'text',
            'user_id' => $ngoUser->id,
            'impressions' => 0,
        ]);

        PostHasLikes::create([
            'user_id' => $volunteer->id,
            'post_id' => $post->id,
            'created_at' => now()->subDays(5),
            'updated_at' => now()->subDays(5),
        ]);

        PostHasComments::create([
            'comment' => 'Count me in.',
            'user_id' => $volunteer->id,
            'post_id' => $post->id,
            'created_at' => now()->subDays(4),
            'updated_at' => now()->subDays(4),
        ]);

        $thread = CircleThread::create([
            'ngo_id' => $ngoUser->id,
            'user_id' => $ngoUser->id,
            'title' => 'Welcome',
            'content' => 'Introduce yourself.',
        ]);

        CircleReply::create([
            'thread_id' => $thread->id,
            'user_id' => $volunteer->id,
            'content' => 'Happy to help.',
            'created_at' => now()->subDays(3),
            'updated_at' => now()->subDays(3),
        ]);

        Message::create([
            'sender_id' => $volunteer->id,
            'receiver_id' => $ngoUser->id,
            'content' => 'Let me know about the next event.',
            'created_at' => now()->subDays(2),
            'updated_at' => now()->subDays(2),
        ]);

        $badge = Badge::create([
            'name' => 'Helper',
            'description' => 'Earned for supporting events.',
            'icon' => 'fluent:ribbon-24-filled',
            'requirement_type' => 'events',
            'requirement_value' => 1,
        ]);
        $volunteer->badges()->attach($badge->id, [
            'awarded_at' => now()->subDay(),
        ]);

        $features = app(ChurnFeatureExtractor::class)->extractFeatures($volunteer->id, $ngoUser->id);

        $expectedKeys = [
            'days_since_last_attendance',
            'total_events_attended',
            'attendance_rate',
            'total_events_registered',
            'posts_liked_last_30_days',
            'comments_made_last_30_days',
            'circle_replies_last_30_days',
            'messages_sent_last_30_days',
            'badges_earned_total',
        ];

        $this->assertSame($expectedKeys, array_keys($features));

        foreach ($features as $value) {
            $this->assertIsFloat($value);
        }
    }

    public function test_risk_score_is_between_zero_and_one(): void
    {
        $service = app(ChurnPredictionService::class);

        $score = $service->predictRiskScore([
            'days_since_last_attendance' => 14.0,
            'total_events_attended' => 3.0,
            'attendance_rate' => 0.60,
            'total_events_registered' => 5.0,
            'posts_liked_last_30_days' => 2.0,
            'comments_made_last_30_days' => 1.0,
            'circle_replies_last_30_days' => 1.0,
            'messages_sent_last_30_days' => 0.0,
            'badges_earned_total' => 2.0,
        ]);

        $this->assertGreaterThanOrEqual(0.0, $score);
        $this->assertLessThanOrEqual(1.0, $score);
    }

    public function test_risk_level_mapping(): void
    {
        $service = app(ChurnPredictionService::class);

        $this->assertSame('low', $service->getRiskLevel(0.2));
        $this->assertSame('medium', $service->getRiskLevel(0.5));
        $this->assertSame('high', $service->getRiskLevel(0.8));
    }

    public function test_batch_predictions_creates_records(): void
    {
        [$ngoUser, $volunteerA, $volunteerB] = $this->createNgoAndTwoVolunteers();

        $events = collect([
            $this->createEventForNgo($ngoUser, 'Event One'),
            $this->createEventForNgo($ngoUser, 'Event Two'),
            $this->createEventForNgo($ngoUser, 'Event Three'),
        ]);

        foreach ($events as $event) {
            $event->volunteers()->attach($volunteerA->id, ['status' => 'accepted', 'created_at' => now(), 'updated_at' => now()]);
            $event->volunteers()->attach($volunteerB->id, ['status' => 'accepted', 'created_at' => now(), 'updated_at' => now()]);
        }

        app(ChurnPredictionService::class)->runBatchPredictions();

        $this->assertDatabaseCount('churn_predictions', 2);
    }

    public function test_ngo_churn_index_returns_only_own_volunteers(): void
    {
        [$ngoA, $volunteerA] = $this->createNgoAndVolunteerPair('ngo-a@example.com', 'volunteer-a@example.com');
        [$ngoB, $volunteerB] = $this->createNgoAndVolunteerPair('ngo-b@example.com', 'volunteer-b@example.com');

        ChurnPrediction::create([
            'volunteer_id' => $volunteerA->id,
            'ngo_id' => $ngoA->id,
            'risk_score' => 0.78,
            'risk_level' => 'high',
            'feature_snapshot' => $this->fakeFeatureSnapshot(),
            'predicted_at' => now(),
        ]);

        ChurnPrediction::create([
            'volunteer_id' => $volunteerB->id,
            'ngo_id' => $ngoB->id,
            'risk_score' => 0.18,
            'risk_level' => 'low',
            'feature_snapshot' => $this->fakeFeatureSnapshot(),
            'predicted_at' => now(),
        ]);

        Sanctum::actingAs($ngoA, ['*']);

        $response = $this->getJson('/api/ngo/churn/volunteers');

        $response->assertOk()
            ->assertJson([
                'success' => true,
            ]);

        $data = $response->json('data');

        $this->assertCount(1, $data);
        $this->assertSame($volunteerA->id, $data[0]['volunteer_id']);
    }

    public function test_high_risk_triggers_notification(): void
    {
        Notification::fake();

        [$ngoUser, $volunteer] = $this->createNgoAndVolunteerPair();

        $mockExtractor = new class extends ChurnFeatureExtractor {
            public function extractFeatures(int $volunteerId, int $ngoId): array
            {
                return [
                    'days_since_last_attendance' => 365.0,
                    'total_events_attended' => 0.0,
                    'attendance_rate' => 0.0,
                    'total_events_registered' => 2.0,
                    'posts_liked_last_30_days' => 0.0,
                    'comments_made_last_30_days' => 0.0,
                    'circle_replies_last_30_days' => 0.0,
                    'messages_sent_last_30_days' => 0.0,
                    'badges_earned_total' => 0.0,
                ];
            }
        };

        $service = new ChurnPredictionService($mockExtractor);
        $service->predictForVolunteer($volunteer->id, $ngoUser->id);

        Notification::assertSentTo($ngoUser, VolunteerChurnAlert::class);
    }

    private function createNgoAndVolunteerPair(
        string $ngoEmail = 'ngo@example.com',
        string $volunteerEmail = 'volunteer@example.com'
    ): array {
        $ngoUser = User::factory()->create([
            'name' => 'Helping Hands',
            'email' => $ngoEmail,
            'role_id' => 1,
            'verified' => true,
        ]);

        Ngo::create([
            'user_id' => $ngoUser->id,
            'ngo_name' => 'Helping Hands NGO',
            'registration_date' => now()->subYear()->toDateString(),
            'category' => 'Health',
            'address' => 'Kathmandu',
            'phone' => '9800000000',
            'registration_number' => 'REG123',
            'registration_district' => 'Kathmandu',
            'last_renewal_date' => now()->subMonths(3)->toDateString(),
            'pan_number' => '1234567890',
            'mission' => 'Support communities.',
            'description' => 'Community-driven NGO.',
            'verified' => true,
        ]);

        $volunteer = User::factory()->create([
            'name' => 'Jane Volunteer',
            'email' => $volunteerEmail,
            'role_id' => 2,
            'verified' => true,
        ]);

        return [$ngoUser, $volunteer];
    }

    private function createNgoAndTwoVolunteers(): array
    {
        [$ngoUser, $volunteerA] = $this->createNgoAndVolunteerPair('ngo-main@example.com', 'volunteer-one@example.com');

        $volunteerB = User::factory()->create([
            'name' => 'John Volunteer',
            'email' => 'volunteer-two@example.com',
            'role_id' => 2,
            'verified' => true,
        ]);

        return [$ngoUser, $volunteerA, $volunteerB];
    }

    private function createEventForNgo(User $ngoUser, string $title = 'Community Outreach'): Event
    {
        return Event::create([
            'title' => $title,
            'description' => 'Support the local community.',
            'requirements' => 'Commitment and teamwork.',
            'location' => 'Kathmandu',
            'type' => 1,
            'category' => 'Health',
            'start_date' => now()->addDays(7),
            'end_date' => now()->addDays(8),
            'capacity' => '50',
            'is_volunteers_required' => true,
            'user_id' => $ngoUser->id,
        ]);
    }

    private function fakeFeatureSnapshot(): array
    {
        return [
            'days_since_last_attendance' => 10.0,
            'total_events_attended' => 3.0,
            'attendance_rate' => 0.75,
            'total_events_registered' => 4.0,
            'posts_liked_last_30_days' => 2.0,
            'comments_made_last_30_days' => 1.0,
            'circle_replies_last_30_days' => 0.0,
            'messages_sent_last_30_days' => 1.0,
            'badges_earned_total' => 1.0,
        ];
    }
}
