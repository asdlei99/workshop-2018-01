<?php

namespace App\Http\Controllers\WebApi;

use App\Comment;
use App\Http\Requests\AddComment;
use App\Http\ReturnHelper;
use App\Post;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class CommentController extends Controller
{
    public function addToPost(AddComment $request ,$id)
    {
        $post = Post::find($id);
        if(null === $post){
            return ReturnHelper::returnWithStatus(null,Response::HTTP_NOT_FOUND,'Post Not Found');
        }

        $comment = new Comment();
        $comment->post_id = $id;
        $comment->user_id = $request->input('user_id',1);
//        $comment->user_id = $request->user()->id;
        $comment->parent_id = 0;
        $comment->level = 1;
        $comment->body = $request->input('body');
        $comment->created_at = Carbon::now();
        try{
            $comment->save();
        }catch (\Exception $e){
            return ReturnHelper::returnWithStatus(['errors' => $e->getMessage()],Response::HTTP_FORBIDDEN,'Comment Save Failed');
        }

        return ReturnHelper::returnWithStatus(['post_id' => $comment->post_id]);
    }

    public function addToComment(AddComment $request, $id)
    {
        $parent_comment = Comment::find($id);
        if(null === $parent_comment){
            return ReturnHelper::returnWithStatus(null,Response::HTTP_NOT_FOUND,'Comment Not Found');
        }

        $comment = new Comment();
        $comment->post_id = $parent_comment->post_id;
        $comment->user_id = 2;
        $comment->parent_id = $id;
        $comment->level = ($parent_comment->level == 1 )? 2 : 3;
        $comment->body = $request->input('body');
        $comment->created_at = Carbon::now();

        try{
            $comment->save();
        }catch (\Exception $e){
            return ReturnHelper::returnWithStatus(['errors' => $e->getMessage()],Response::HTTP_FORBIDDEN,'Comment Save Failed');
        }

        return ReturnHelper::returnWithStatus(['post_id'=>$comment->post_id]);
    }

    public function destroy($id)
    {
        $parent_comment = Comment::find($id);
        if(null === $parent_comment){
            return ReturnHelper::returnWithStatus(null,Response::HTTP_NOT_FOUND,'Comment Not Found');
        }
//        var_dump($parent_comment->getChildren());
        return ReturnHelper::returnWithStatus($parent_comment->getChildren());

        
    }
}
