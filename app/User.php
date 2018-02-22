<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, SearchTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getPosts()
    {
        return $this->hasMany(Post::class,'user_id','id')->getResults();
    }

    public function posts()
    {
        return $this->hasMany(Post::class,'user_id','id');
    }


    public static function getUserByUsername($username)
    {
        return User::where('username','=',$username)->first();
    }

}
