<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CommentLikeMessageControl extends Model
{
    protected $table = 'comment_like_message_control';

    public static function findByLikeId($like_id)
    {
        return CommentLikeMessageControl::where('like_id',$like_id)->first();
    }

    public static function getByUserId($user_id)
    {
        if(is_a($user_id,User::class)){
            $user_id = $user_id->id;
        }
        return CommentLikeMessageControl::where('user_id',$user_id)->get();
    }

    public function scopeUserId($query,$user_id)
    {
        if(is_a($user_id,User::class)){
            $user_id = $user_id->id;
        }

        return $query->where('user_id',$user_id);
    }

    public function getCommentLike()
    {
        return CommentLike::find($this->like_id);
    }

    public function getComment()
    {
        return $this->getCommentLike()->getComment();
    }
}
