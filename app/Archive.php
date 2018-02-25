<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Archive extends Model
{

    public static function findByName($name)
    {
        return static::where('name',$name)->first();
    }
}
