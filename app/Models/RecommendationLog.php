<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * RecommendationLog Model
 *
 * Stores recommendation engine output for debugging and demo purposes.
 * Each record represents one recommendation served to a user, including
 * the computed score and human-readable reason.
 */
class RecommendationLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'recommendable_type',
        'recommendable_id',
        'score',
        'reason',
    ];

    protected $casts = [
        'score' => 'float',
        'created_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Polymorphic relationship to the recommended item (Ngo or Event).
     */
    public function recommendable()
    {
        return $this->morphTo();
    }
}
