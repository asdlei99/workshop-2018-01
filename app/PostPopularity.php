<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PostPopularity extends Model
{
    use SoftDeletes;
    protected $table = 'post_popularity';
    protected $dates = ['deleted_at'];
    protected $primaryKey = 'post_id';
}
