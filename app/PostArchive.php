<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PostArchive extends Model
{
    use SoftDeletes;
    protected $table = 'post_archive';
    protected $dates = ['deleted_at'];

    
}
