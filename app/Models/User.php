<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable, HasFactory;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'phone',
        'owner_id',
        'verified',
        'suspended',
        'suspension_reason',
        'suspended_at',
        'location',
        'preferred_categories',
        'profile_photo',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'verified' => 'boolean',
        'preferred_categories' => 'array',
    ];


    public function favoriteNgos()
    {
        return $this->belongsToMany(Ngo::class, 'user_ngo_favorites', 'user_id', 'ngo_id')->withTimestamps();
    }

    public function followedNgos(){
        return $this->belongsToMany(
            Ngo::class,
            'follows',
            'user_id',
            'ngo_id',
            'id',
            'user_id'
        )->withTimestamps();
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function ownedNgos()
    {
        return $this->hasMany(User::class, 'owner_id')->where('role_id', 1);
    }

    public function ngo()
    {
        return $this->hasOne(Ngo::class, 'user_id', 'id');
    }

    public function likedPosts(){
        return $this->belongsToMany(Post::class, 'post_has_likes', 'user_id', 'post_id');
    }

    public function isAdmin()
    {
        return $this->role_id === 0;
    }

    public function isNgo()
    {
        return $this->role_id === 1;
    }

    public function isPeople()
    {
        return $this->role_id === 2;
    }

    public function volunteeredEvents()
    {
        return $this->belongsToMany(Event::class, 'event_has_volunteers', 'user_id', 'event_id')
            ->withPivot('status')
            ->withTimestamps();
    }

    public function donations()
    {
        return $this->hasMany(Donation::class, 'user_id');
    }

    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }

    public function badges()
    {
        return $this->belongsToMany(Badge::class, 'user_badges', 'user_id', 'badge_id')
                    ->withPivot('awarded_at')
                    ->withTimestamps();
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    public function threadsAsNgo()
    {
        return $this->hasMany(CircleThread::class, 'ngo_id');
    }

    public function threadsAsUser()
    {
        return $this->hasMany(CircleThread::class, 'user_id');
    }

    public function circleReplies()
    {
        return $this->hasMany(CircleReply::class, 'user_id');
    }

    public function isVerified()
    {
        return $this->verified === true;
    }
}
