<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    public function getUser()
    {
        return $this->belongsTo(User::class,'user_id','id')->getResults();
    }

//    public function comments()
//    {
//        return $this->hasMany(Comment::class,'post_id','id');
//    }

    public function getArchive()
    {
        $archive = $this->belongsToMany(
            Archive::class,
            'post_archive',
            'post_id',
            'archive_id'
            )->getResults()[0];
        $parent_archive = Archive::find($archive->parent_id);
        return [$parent_archive,$archive];
//        return $archive;
    }
}
