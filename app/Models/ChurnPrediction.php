<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChurnPrediction extends Model
{
    use HasFactory;

    protected $fillable = [
        'volunteer_id',
        'ngo_id',
        'risk_score',
        'risk_level',
        'feature_snapshot',
        'predicted_at',
    ];

    protected $casts = [
        'feature_snapshot' => 'array',
        'risk_score' => 'float',
        'predicted_at' => 'datetime',
    ];

    public function volunteer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'volunteer_id');
    }

    public function ngo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'ngo_id');
    }
}
