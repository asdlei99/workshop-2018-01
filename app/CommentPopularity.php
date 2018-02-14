<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CommentPopularity extends Model
{
    //只有level 1 的评论有commentPopularity，
    //即第一层评论才会统计这些信息。
    use SoftDeletes;
    protected $table = 'comment_popularity';
    protected $dates = ['deleted_at'];
    protected $primaryKey = 'comment_id';
}
