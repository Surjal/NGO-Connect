<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EventCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * This seeder backfills the 'category' column on the events table
     * using the category from the NGO (User -> Ngo profile) that created the event.
     * It is required for the AI Recommendation Engine to work accurately.
     */
    public function run(): void
    {
        $events = Event::with('ngo')->get();
        $updatedCount = 0;

        foreach ($events as $event) {
            if ($event->ngo && $event->ngo->category) {
                // Update directly using DB to avoid firing events/timestamps 
                // if we just want a quiet data backfill
                DB::table('events')
                    ->where('id', $event->id)
                    ->update(['category' => $event->ngo->category]);
                
                $updatedCount++;
            }
        }

        $this->command->info("Backfilled categories for {$updatedCount} events based on their parent NGOs.");
        Log::info("EventCategorySeeder: updated {$updatedCount} events with categories.");
    }
}
