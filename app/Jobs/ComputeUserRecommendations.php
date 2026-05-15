<?php

namespace App\Jobs;

use App\Models\User;
use App\Services\RecommendationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ComputeUserRecommendations implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public int $userId)
    {
        $this->queue = 'recommendations';
    }

    public function handle(RecommendationService $service): void
    {
        $user = User::find($this->userId);

        if (!$user) {
            return;
        }

        $service->computeAndStoreRecommendations($user);
    }
}
