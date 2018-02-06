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

    public function children()
    {
        $this->hasMany(Comment::class,'parent_id','id')->getResults();
    }

    public function getChildrenAndGrandChildren()
    {
        $children = $this->hasMany(Comment::class,'parent_id','id')->getResults();

        $data = []; $i = 0;
        foreach ($children as $child){
            $data[$i++]['child'] = $child;
            $grand_children = $child->hasMany(Comment::class,'parent_id','id')->getResults();
            $data[$i++]['grand_children'] = $grand_children;

        }
        return $data;
    }

    public function deleteSelfAndChildren()
    {
        if($this->level == 1){
            $children = $this->hasMany(Comment::class,'parent_id','id')->getResults();
            foreach ($children as $child){
                $grand_children = $child->hasMany(Comment::class,'parent_id','id')->getResults();
                foreach ($grand_children as $grand_child){
                    $grand_child->body = '由于父评论删除，该评论隐藏';
                    $grand_child->save();
                }
            }
            foreach ($children as $child) {
                $child->body = '由于父评论删除，该评论隐藏';
                $child->save();
            }
        }elseif($this->level == 2){
            $children = $this->hasMany(Comment::class,'parent_id','id')->getResults();
            foreach ($children as $child) {
                $child->body = '由于父评论删除，该评论隐藏';
                $child->save();
            }
        }
        $this->body = '该评论已删除';
        $this->save();


    }

}
