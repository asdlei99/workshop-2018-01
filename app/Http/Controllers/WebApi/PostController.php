<?php

namespace App\Http\Controllers\WebApi;

use App\Archive;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\ReturnHelper;
use App\Post;
use App\PostArchive;
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
            return ReturnHelper::returnWithStatus('未找到指定文章',2003);
        }
        return ReturnHelper::returnWithStatus(Fractal::includes('user')->item($post,new PostTransformer()));
    }

    public function store(StorePostRequest $request)
    {
        //这里验证archive是否合法
        $archive = Archive::find($request->input('archive'));
        if(! ($archive !== null && $archive->parent_id > 0)){
            return ReturnHelper::returnWithStatus('类别不合理',2002);
        }


        $post = new Post();
        $post->title = $request->input('title');
        $post->body = $request->input('body');
        $post->user_id = session('user')->id;
        $post->anonymous = $request->input('anonymous');
        if($request->input('description')){
            $post->description = $request->input('description');
        }else{
            $post->description = $post->title;
        }

        $post_archive = new PostArchive();
        $post_archive->archive_id = $request->input('archive');

        try{
            $post->save();
            $post_archive->post_id = $post->id;
            $post_archive->save();
        }catch (\Exception $e) {
            return ReturnHelper::returnWithStatus(['errors' => '文章储存失败，请稍后重试'], 2002);
//            return ReturnHelper::returnWithStatus(['errors'=>$e->getMessage()],2002,'文章储存失败');
        }
        return ReturnHelper::returnWithStatus(Fractal::item($post, new PostTransformer()));
    }

    public function update(StorePostRequest $request, $id)
    {
        $post = Post::find($id);
        if($post === null){
            return ReturnHelper::returnWithStatus('未找到指定文章',2003);
        }

        // \App\User
        $user = session('auth');
        if($user->id !== $post->user_id){
            return ReturnHelper::returnWithStatus('您可能并非文章作者，权限不足',2004);
        }

        //这里验证archive是否合法
        $archive = Archive::find($request->input('archive'));
        if(! ($archive !== null && $archive->parent_id > 0)){
            return ReturnHelper::returnWithStatus('类别不合理',2002);
        }

        $post->title = $request->input('title');
        $post->body = $request->input('body');
        $post->anonymous = $request->input('anonymous');
        if($request->input('description')){
            $post->description = $request->input('description');
        }else{
            $post->description = $post->title;
        }

        $post_archive = PostArchive::where('post_id','=',$post->id);
        $post_archive->archive_id = $request->input('archive');

        try{
            $post->save();
            $post_archive->save();
        }catch (\Exception $e) {
            return ReturnHelper::returnWithStatus(['errors' => '文章储存失败，请稍后重试'], 2002);
//            return ReturnHelper::returnWithStatus(['errors'=>$e->getMessage()],2002,'文章储存失败');
        }
        return ReturnHelper::returnWithStatus(Fractal::item($post, new PostTransformer()));

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
