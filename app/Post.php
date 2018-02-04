<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class)->getResults();
    }

    public function comments()
    {
        return $this->hasMany(Comment::class,'post_id','id');
    }
}
