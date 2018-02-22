<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostLike extends Model
{
    protected $table = 'post_likes';
    public $timestamps = false;

    public function getPost()
    {
        return Post::find($this->post_id);
    }

}
