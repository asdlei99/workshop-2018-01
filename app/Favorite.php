<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    // post_favorite
    protected $table = 'favorites';
    public $timestamps = false;

    public static function getByUserId($user_id)
    {
        if(is_a($user_id,User::class)){
            $user_id = $user_id->id;
        }
        return static::where('user_id',$user_id)->get();
    }

    public function scopeUserId($query, $user_id)
    {
        if(is_a($user_id,User::class)){
            $user_id = $user_id->id;
        }

        return $query->where('user_id',$user_id);
    }

}
