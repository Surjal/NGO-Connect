<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventMilestone extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'title',
        'description',
        'status',
        'order',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class, 'milestone_id');
    }
}
