<?php

namespace App\Http\Controllers\WebApi;

use App\CommentLike;
use App\Http\ReturnHelper;
use App\PostLike;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Post;
use App\Comment;

class LikeController extends Controller
{
    public function likePost($id)
    {
        if(Post::find($id) === null){
            return ReturnHelper::returnWithStatus('未找到指定文章',2003);
        }

        $user_id = session('user')->id;
        $post_like = PostLike::where('user_id',$user_id)->where('post_id',$id)->first();
        if($post_like !== null){
            //点过赞了，那就取消赞
            try{
                $post_like->delete();
            }catch (\Exception $e){
                return ReturnHelper::returnWithStatus('取消赞失败',4002);
            }
            return ReturnHelper::returnWithStatus('取消赞成功',4102);
        }else{
            //没点过赞就点赞
            $post_like = new PostLike();
            $post_like->user_id = $user_id;
            $post_like->post_id = $id;
            $post_like->created_at = Carbon::now();
            try{
                $post_like->save();
            }catch (\Exception $e){
                return ReturnHelper::returnWithStatus('点赞失败',4001);
            }
            return ReturnHelper::returnWithStatus('点赞成功',4101);
        }
    }

    public function likeComment($id){
        if(Comment::find($id) === null){
            return ReturnHelper::returnWithStatus('未找到指定评论',3002);
        }

        $user_id = session('user')->id;
        $commment_like = CommentLike::where('user_id',$user_id)->where('comment_id',$id)->first();
        if($commment_like !== null){
            //点过赞了，那就取消赞
            try{
                $commment_like->delete();
            }catch (\Exception $e){
                return ReturnHelper::returnWithStatus('取消赞失败',4002);
            }
            return ReturnHelper::returnWithStatus('取消赞成功',4102);
        }else{
            //没点过赞就点赞
            $commment_like = new CommentLike();
            $commment_like->user_id = $user_id;
            $commment_like->comment_id = $id;
            $commment_like->created_at = Carbon::now();
            try{
                $commment_like->save();
            }catch (\Exception $e){
                return ReturnHelper::returnWithStatus('点赞失败',4001);
            }
            return ReturnHelper::returnWithStatus('点赞成功',4101);
        }
    }
}
