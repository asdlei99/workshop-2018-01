<?php

namespace App\Http\Controllers\WebApi;

use App\CommentLike;
use App\CommentLikeMessageControl;
use App\Http\ReturnHelper;
use App\PostLike;
use App\PostLikeMessageControl;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Post;
use App\Comment;

class LikeController extends Controller
{
    public function likePost($id)
    {
        $post = Post::find($id);
        if($post === null){
            return ReturnHelper::returnWithStatus('未找到指定文章',2003);
        }

        $user_id = session('user')->id;

        $post_popularity = $post->getPopularity();

        $post_like = PostLike::where('user_id',$user_id)->where('post_id',$id)->first();


        if($post_like !== null){
            //取消赞
            $post_popularity->like_count = $post_popularity->like_count - 1;

            $post_like_message = PostLikeMessageControl::findByLikeId($post_like->id);

            try{
                $post_popularity->save();
                $post_like->delete();
                $post_like_message->delete();

            }catch (\Exception $e){
                return ReturnHelper::returnWithStatus('取消赞失败',4002);
            }
            return ReturnHelper::returnWithStatus('取消赞成功',4102);

        }else{
            //点赞

            $post_like = new PostLike();
            $post_like->user_id = $user_id;
            $post_like->post_id = $id;
            $post_like->created_at = Carbon::now();

            $post_popularity->like_count = $post_popularity->like_count + 1;

            $post_like_message = new PostLikeMessageControl();
            $post_like_message->user_id = $post->user_id;

            try{
                $post_popularity->save();
                $post_like->save();

                $post_like_message->like_id = $post_like->id;
                $post_like_message->save();

            }catch (\Exception $e){
                return ReturnHelper::returnWithStatus('点赞失败',4001);
            }
            return ReturnHelper::returnWithStatus('点赞成功',4101);
        }
    }

    public function likeComment($id){
        $comment = Comment::find($id);
        if($comment === null){
            return ReturnHelper::returnWithStatus('未找到指定评论',3002);
        }

        $user_id = session('user')->id;
        $comment_like = CommentLike::where('user_id',$user_id)->where('comment_id',$id)->first();
        $comment_popularity = $comment->getPopularity();

        if($comment_like !== null){
            //取消赞
            $comment_popularity->like_count = $comment_popularity->like_count - 1;

            $comment_like_control = CommentLikeMessageControl::findByLikeId($comment_like->id);

            try{
                $comment_like->delete();
                $comment_popularity->save();
                $comment_like_control->delete();
            }catch (\Exception $e){
                return ReturnHelper::returnWithStatus('取消赞失败',4002);
            }
            return ReturnHelper::returnWithStatus('取消赞成功',4102);
        }else{
            //点赞
            $comment_like = new CommentLike();
            $comment_like->user_id = $user_id;
            $comment_like->comment_id = $id;
            $comment_like->created_at = Carbon::now();

            $comment_popularity->like_count = $comment_popularity->like_count + 1;

            $comment_like_control = new CommentLikeMessageControl();
            $comment_like_control->user_id = $comment->user_id;

            try{
                $comment_like->save();
                $comment_popularity->save();

                $comment_like_control->like_id = $comment_like->id;
                $comment_like_control->save();
            }catch (\Exception $e){
                return ReturnHelper::returnWithStatus('点赞失败',4001);
            }
            return ReturnHelper::returnWithStatus('点赞成功',4101);
        }
    }
}
