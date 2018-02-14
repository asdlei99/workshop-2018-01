<?php

namespace App\Http\Controllers\WebApi;

use App\Comment;
use App\Http\Requests\AddComment;
use App\Http\ReturnHelper;
use App\Post;
use App\Transformers\CommentTransformer;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Cyvelnet\Laravel5Fractal\Facades\Fractal;
use Illuminate\Http\Response;
use function Sodium\crypto_box_publickey_from_secretkey;

class CommentController extends Controller
{
    public function addToPost(AddComment $request ,$id)
    {
        $post = Post::find($id);
        if(null === $post){
            return ReturnHelper::returnWithStatus('未找到指定文章',2003);
        }

        $comment = new Comment();
        $comment->post_id = $id;
        $comment->user_id = session('user')->id;
        $comment->parent_id = 0;
        $comment->level = 1;
        $comment->body = $request->input('body');
        try{
            $comment->save();
        }catch (\Exception $e){
            return ReturnHelper::returnWithStatus('评论失败',3001);
        }

        return ReturnHelper::returnWithStatus(Fractal::item($comment,new CommentTransformer()));
    }

    public function addToComment(AddComment $request, $id)
    {
        $parent_comment = Comment::find($id);
        if(null === $parent_comment){
            return ReturnHelper::returnWithStatus('未找到指定评论',3002);
        }

        $comment = new Comment();
        $comment->post_id = $parent_comment->post_id;
        $comment->user_id = session('user')->id;
        $comment->parent_id = ($parent_comment->level === 3 ) ? $parent_comment->parent_id : $id;
        $comment->level = ($parent_comment->level == 1 )? 2 : 3;
        $comment->body = $request->input('body');

        try{
            $comment->save();
        }catch (\Exception $e){
            return ReturnHelper::returnWithStatus('评论失败',3001);
        }

        return ReturnHelper::returnWithStatus(Fractal::item($comment,new CommentTransformer()));
    }

    public function destroy($id)
    {
        $comment = Comment::find($id);
        if(null === $comment){
            return ReturnHelper::returnWithStatus('未找到指定评论',3002);
        }

        try{
            $comment->deleteSelfAndChildren();
        }catch (\Exception $e){
            return ReturnHelper::returnWithStatus('删除评论失败',3001);
        }

        return ReturnHelper::returnWithStatus('删除评论成功');
    }

    public function getPostComments($post_id)
    {
        $post = Post::find($post_id);
        $comments = $post->getComments();
        return ReturnHelper::returnWithStatus(Fractal::collection($comments, new CommentTransformer()));
    }

}
