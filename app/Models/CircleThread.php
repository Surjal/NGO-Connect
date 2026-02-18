<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CircleThread extends Model
{
    use HasFactory;

    protected $fillable = [
        'ngo_id',
        'user_id',
        'title',
        'content',
    ];

    public function ngo()
    {
        return $this->belongsTo(User::class, 'ngo_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function replies()
    {
        return $this->hasMany(CircleReply::class, 'thread_id');
    }
}
