<?php

namespace App\Console\Commands;

use App\Services\ChurnPredictionService;
use Illuminate\Console\Command;

class RunChurnPredictions extends Command
{
    protected $signature = 'churn:predict';

    protected $description = 'Run volunteer churn prediction for all active NGO-volunteer pairs';

    public function handle(ChurnPredictionService $churnPredictionService): int
    {
        $churnPredictionService->runBatchPredictions();

        $count = $churnPredictionService->getLastBatchCount();

        $this->info('Volunteer churn predictions completed.');
        $this->line('Predictions run: ' . $count);

        return self::SUCCESS;
    }
}
