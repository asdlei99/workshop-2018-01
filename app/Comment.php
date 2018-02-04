<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    public $timestamps = false;

    public $fillable = ['post_id','user_id','parent_id','level','body','created_at'];

    public function post()
    {
        return $this->belongsTo(Post::class,'post_id','id')->getResults();
    }

    public function getChildren()
    {
        $children = $this->hasMany(Comment::class,'parent_id','id')->getResults();
        $i =0;
        foreach ($children as $child){
            $data[] = $child;
            $data[$i][] = $child->hasMany(Comment::class,'parent_id','id')->getResults();
            $i++;
//            $grand_children = $child->hasMany(Comment::class,'parent_id','id')->getResults();
        }
//        return [$children,$grand_children];
        return $data;
    }

}
