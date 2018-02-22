<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostLikeMessageControl extends Model
{
    protected $table = 'post_like_message_control';

    public static function findByLikeId($like_id)
    {
        return PostLikeMessageControl::where('like_id',$like_id)->first();
    }

    public static function getByUserId($user_id)
    {
        if(is_a($user_id,User::class)){
            $user_id = $user_id->id;
        }
        return static::where('user_id',$user_id)->get();
    }

    public function scopeUserId($query,$user_id)
    {
        if(is_a($user_id,User::class)){
            $user_id = $user_id->id;
        }

        return $query->where('user_id',$user_id);
    }

    public function getPostLike()
    {
        return PostLike::find($this->like_id);
    }

    public function getPost()
    {
        return $this->getPostLike()->getPost();
    }
}
