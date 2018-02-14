<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    /**
     * 只能获取一层Children
     */
    public function getChildren()
    {
        return $this->hasMany(Comment::class,'parent_id','id')->getResults();
    }

    /**
     * 如果有两层children，两层children都会被删除
     */
    public function deleteSelfAndChildren()
    {
        //感觉这里写的非常糟糕
        if($this->level === 3){
            $this->delete();
        }elseif($this->level === 2){
            $children = $this->getChildren();
            foreach ($children as $child){
                $child->delete();
            }
            $this->delete();
        }else{
            $children = $this->getChildren();
            foreach ($children as $child){
                $grand_children = $child->getChildren();
                foreach ($grand_children as $grand_child){
                    $grand_child->delete();
                }
                $child->delete();
            }
            $this->delete();
        }
    }


//    public function getPost()
//    {
//        return $this->belongsTo(Post::class,'post_id','id')->getResults();
//    }
//

}
