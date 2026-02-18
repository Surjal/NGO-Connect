<?php

use App\Models\Event;
use App\Models\User;
use Illuminate\Support\Facades\DB;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

echo "--- Debugging Volunteer Visibility ---\n";

// 1. Find the event
$event = Event::where('title', 'like', '%Art Competition for Kids%')->first();

if (!$event) {
    echo "Event 'Art Competition for Kids' not found.\n";
    exit;
}

echo "Event Found: {$event->title} (ID: {$event->id})\n";

// 2. Check Raw Pivot
$pivotRecords = DB::table('event_has_volunteers')->where('event_id', $event->id)->get();
echo "Raw Pivot Count: " . $pivotRecords->count() . "\n";

foreach ($pivotRecords as $p) {
    echo "  - User ID: {$p->user_id} | Status: {$p->status}\n";
    
    // 3. Check if User exists raw
    $rawUser = DB::table('users')->find($p->user_id);
    echo "    -> User exists in DB? " . ($rawUser ? "YES" : "NO") . "\n";
    if ($rawUser) {
        echo "       Name: {$rawUser->name}, Role: {$rawUser->role_id}, Verified: {$rawUser->verified}\n";
    }

    // 4. Check Eloquent User
    $eloquentUser = User::find($p->user_id);
    echo "    -> User findable via Eloquent? " . ($eloquentUser ? "YES" : "NO") . "\n";
}

// 5. Check Relationship
$volunteers = $event->volunteers;
echo "Eloquent 'volunteers' Relation Count: " . $volunteers->count() . "\n";
foreach ($volunteers as $v) {
    echo "  - Vol: {$v->name} (ID: {$v->id})\n";
}

if ($pivotRecords->count() > 0 && $volunteers->count() === 0) {
    echo "\n!!! DISCREPANCY DETECTED !!!\n";
    echo "Pivot has records, but Eloquent relation is empty.\n";
    echo "This usually means the User model has a Global Scope hiding these users.\n";
}
