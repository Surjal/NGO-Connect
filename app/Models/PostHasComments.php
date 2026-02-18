<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostHasComments extends Model
{
    use HasFactory;
    protected $fillable = [
        'comment',
        'user_id',
        'post_id',
        'parent_id'
    ];

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function replies(){
        return $this->hasMany(PostHasComments::class,'parent_id');
    }

    public function parent(){
        return $this->belongsTo(PostHasComments::class,'parent_id');
    }
}
