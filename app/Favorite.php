<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    // post_favorite
    protected $table = 'favorites';
    public $timestamps = false;


}
