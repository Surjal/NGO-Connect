<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRecommendation extends Model
{
    protected $fillable = [
        'user_id',
        'recommendations',
        'computed_at',
    ];

    protected $casts = [
        'recommendations' => 'array',
        'computed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
