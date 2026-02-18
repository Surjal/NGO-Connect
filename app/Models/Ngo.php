<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Ngo extends Model
{
    use Notifiable;

    protected $fillable = [
        'user_id',
        'ngo_name',
        'registration_date',
        'category',
        'subcategory',
        'address',
        'phone',
        'registration_number',
        'registration_district',
        'last_renewal_date',
        'pan_number',
        'mission',
        'contact_position',
        'description',
        'logo',
        'suspended',
        'suspension_reason',
        'suspended_at',
        'verified'
    ];

    protected $casts = [
        'photos' => 'array',
        'verified' => 'boolean',
        'suspended' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'follows', 'ngo_id', 'user_id')
            ->withTimestamps();
    }

    public function events()
    {
        return $this->hasMany(Event::class, 'user_id');
    }

    public function donations()
    {
        return $this->hasMany(Donation::class);
    }
}
