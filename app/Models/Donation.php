<?php

namespace App\Models;

use App\Jobs\ComputeUserRecommendations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'ngo_id',
        'donation_amount',
    ];

    protected static function booted(): void
    {
        static::created(function (Donation $donation) {
            ComputeUserRecommendations::dispatch($donation->user_id)->afterCommit();
        });

        static::deleted(function (Donation $donation) {
            ComputeUserRecommendations::dispatch($donation->user_id)->afterCommit();
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ngo()
    {
        return $this->belongsTo(User::class, 'ngo_id');
    }
}
