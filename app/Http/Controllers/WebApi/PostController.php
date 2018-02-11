<?php

namespace App\Http\Controllers\WebApi;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\ReturnHelper;
use App\Post;
use App\Transformers\PostTransformer;
use Cyvelnet\Laravel5Fractal\Facades\Fractal;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;


class PostController extends Controller
{
    public function index()
    {
        $cnt = \request('cnt') ? \request('cnt'): 3;
        $posts = Post::take($cnt)->get();

        return ReturnHelper::returnWithStatus(
            Fractal::includes('user')->collection($posts,new PostTransformer())
        );

    }


    public function show($id)
    {
        $post = Post::find($id);
        if($post === null){
            return ReturnHelper::returnWithStatus(null,Response::HTTP_NOT_FOUND,'Not Found');
        }

        return ReturnHelper::returnWithStatus(Fractal::includes('user')->item($post,new PostTransformer()));
    }

    public function store(StorePostRequest $request)
    {
        $post = new Post();
        $post->title = $request->input('title');
        $post->body = $request->input('body');
        if($request->input('description')){
            $post->description = $request->input('description');
        }else{
            $post->description = $post->title;
        }

        $post->user_id = session('user')->id;
        $post->anonymous = $request->input('anonymous');
        try{
            $post->save();
        }catch (\Exception $e){
            return ReturnHelper::returnWithStatus(['errors'=>'文章储存失败，请稍后重试'],2002,'文章储存失败');
//            return ReturnHelper::returnWithStatus(['errors'=>$e->getMessage()],2002,'文章储存失败');
        }
        //TODO archive 存入 post_archive表
        return ReturnHelper::returnWithStatus(Fractal::includes('user')->item($post, new PostTransformer()));
    }

    public function update(Request $request, $id)
    {
        $post = Post::find($id);
        if($post === null){
            return ReturnHelper::returnWithStatus(null,Response::HTTP_NOT_FOUND,'Not Found');
        }

        //TODO 需要判断是否认证通过

       if($request->isMethod('patch')){
           $post->title = $request->input('title',$post->title);
           $post->description = $request->input('description',$post->description);
           $post->body = $request->input('body',$post->body);
           $post->anonymous = $request->input('anonymous',$post->anonymous);
       }
       $post->save();

       return ReturnHelper::returnWithStatus(Fractal::includes('user')->item($post, new PostTransformer()));
    }

    public function destroy($id)
    {
        //TODO 判断是否认证通过

        $post = Post::find($id);
        if($post === null){
            return ReturnHelper::returnWithStatus(null,Response::HTTP_NOT_FOUND,'Post Not Found');
        }
        try{
            $post->delete();
        }catch (\Exception $e){
            return ReturnHelper::returnWithStatus(null,Response::HTTP_PRECONDITION_FAILED,'Deleted Failed');
//            return $e->getMessage();
        }

        return ReturnHelper::returnWithStatus('true');

    }
}
