<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'title',
        'description',
        'requirements',
        'location',
        'type',
        'category',
        'start_date',
        'end_date',
        'cover_image_path_name',
        'capacity',
        'is_volunteers_required',
        'user_id',
        'check_in_token',
    ];

    /**
     * Get the NGO profile associated with the event creator.
     * Events are created by NGO users, so we go User -> Ngo.
     */
    public function ngo()
    {
        return $this->hasOneThrough(
            \App\Models\Ngo::class,
            \App\Models\User::class,
            'id',        // users.id
            'user_id',   // ngos.user_id
            'user_id',   // events.user_id
            'id'         // users.id
        );
    }

    /**
     * The "booted" method of the model.
     */
    protected static function booted()
    {
        static::saving(function ($event) {
            if (empty($event->check_in_token)) {
                $event->check_in_token = bin2hex(random_bytes(16));
            }
        });
    }

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_volunteers_required' => 'boolean',
        'type' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function volunteers()
    {
        return $this->belongsToMany(User::class, 'event_has_volunteers', 'event_id', 'user_id')->withTimestamps()->withPivot('status');
    }

    

public function getStatusAttribute()
{
    $now = Carbon::now();

    // Ensure start_date and end_date are Carbon instances
    $start = Carbon::parse($this->start_date);
    $end = Carbon::parse($this->end_date);

    if ($start->isFuture()) {
        return 'upcoming';
    } elseif ($now->between($start, $end)) {
        return 'live';
    } elseif ($end->isPast()) {
        return 'completed';
    }

    return 'unknown';
}

public function certificates()
{
    return $this->hasMany(Certificate::class);
}

public function attendances()
{
    return $this->hasMany(Attendance::class);
}

public function milestones()
{
    return $this->hasMany(EventMilestone::class, 'event_id')->orderBy('order');
}

}
