<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use function Sodium\crypto_box_publickey_from_secretkey;

class CommentMessageControl extends Model
{
    protected $table = 'comment_message_control';

//    public static function getByCommentId($comment_id)
//    {
//        return CommentMessageControl::where('comment_id',$comment_id)->get();
//    }

    public static function getByUserId($user_id)
    {
        if(is_a($user_id,User::class)){
            $user_id = $user_id->id;
        }
        return CommentMessageControl::where('user_id',$user_id)->get();
    }

}
