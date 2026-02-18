<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'type',
        'user_id',
        'impressions',
        'milestone_id',
    ];

    /** -------------------------------------------------------------
     *  Dynamic attributes that are always appended
     *  ------------------------------------------------------------- */
    protected $appends = [
        'is_liked',
        'is_following',
        'reports_count',
        // 'user_reported',  // ← NEW
    ];

    // Add this accessor
    public function getUserReportedAttribute(): bool
    {
        return $this->attributes['user_reported'] ?? false;
    }
    /** -------------------------------------------------------------
     *  Accessors – return the value (with safe defaults)
     *  ------------------------------------------------------------- */
    public function getIsLikedAttribute(): bool
    {
        // The controller sets the attribute via setAttribute()
        return $this->attributes['is_liked'] ?? false;
    }

    public function getIsFollowingAttribute(): bool
    {
        return $this->attributes['is_following'] ?? false;
    }

    public function getReportsCountAttribute(): int
    {
        return $this->attributes['reports_count'] ?? 0;
    }

    /** -------------------------------------------------------------
     *  Relationships – **exactly as you had them**
     *  ------------------------------------------------------------- */
    // get the User who created the Post
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->with('ngo');
    }

    // get the likes of the Post
    public function likes()
    {
        return $this->hasMany(PostHasLikes::class, 'post_id');
    }

    public function likedUsers()
    {
        return $this->belongsToMany(User::class, 'likes');
    }

    // get the comments of the Post
    public function comments()
    {
        return $this->hasMany(PostHasComments::class, 'post_id');
    }

    public function medias()
    {
        return $this->hasMany(Media::class, 'post_id');
    }

    public function milestone()
    {
        return $this->belongsTo(EventMilestone::class, 'milestone_id');
    }
}
