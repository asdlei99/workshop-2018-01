<?php

namespace App\Http\Controllers\WebApi;

use App\Archive;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\ReturnHelper;
use App\Post;
use App\PostArchive;
use App\PostPopularity;
use App\Transformers\PostTransformer;
use Cyvelnet\Laravel5Fractal\Facades\Fractal;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;


class PostController extends Controller
{
    public function index(Request $request)
    {
        $cnt = $request->input('cnt',15);
        $posts = Post::take($cnt)->orderBy('id', 'desc')->simplePaginate($cnt);

        return ReturnHelper::returnWithStatus(
            Fractal::collection($posts,new PostTransformer())
        );
    }

    public function show($id)
    {
        $post = Post::find($id);
        $post_popularity = $post->getPopularity();
        $post_popularity->view_count  = $post_popularity->view_count + 1;
        $post_popularity->save();
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
        $post_popularity = new PostPopularity();
        $post_archive->archive_id = $request->input('archive');

        try{
            $post->save();
            $post_archive->post_id = $post->id;
            $post_popularity->post_id = $post->id;
            $post_archive->save();
            $post_popularity->save();
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
        $user = session('user');
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

        $post_archive = PostArchive::where('post_id','=',$post->id)->first();
        $post_archive->archive_id = $request->input('archive');

        try{
            $post->save();
            $post_archive->save();
        }catch (\Exception $e) {
            return ReturnHelper::returnWithStatus('文章储存失败，请稍后重试', 2002);
        }
        return ReturnHelper::returnWithStatus(Fractal::item($post, new PostTransformer()));
    }

    public function destroy($id)
    {
        $post = Post::find($id);
        if($post === null){
            return ReturnHelper::returnWithStatus('未找到指定文章',2003);
        }
        if(session('user')->id !== $post->user_id){
            return ReturnHelper::returnWithStatus('您可能并非文章作者，权限不足',2004);
        }

        $post_archive = PostArchive::where('post_id',$id)->first();
        $post_popularity = $post->getPopularity();
        try{
            $post->delete();
            $post_archive->delete();
            $post_popularity->delete();
        }catch (\Exception $e){
            return ReturnHelper::returnWithStatus('文章删除失败，请稍后重试',2002);
        }
        return ReturnHelper::returnWithStatus('true');
    }

    public function showByArchive(Request $request, $id)
    {
        $cnt = $request->input('cnt',15);
        $archive = Archive::find($id);
        if($archive === null){
            return ReturnHelper::returnWithStatus('类别不存在',2005);
        }
        if($archive->parent_id === 0){
            $archives = Archive::where('parent_id','=',$archive->id)->pluck('id')->toArray();
            $post_archives = PostArchive::whereIn('archive_id', $archives)->simplePaginate($cnt)->toArray();
        }else{
            $post_archives = PostArchive::where('archive_id',$archive->id)->simplePaginate($cnt)->toArray();
        }
        $post_ids = array_map(function($post_archive){return $post_archive['post_id'];},$post_archives['data']);

        $posts = Post::whereIn('id',$post_ids)->simplePaginate($cnt);
        return ReturnHelper::returnWithStatus(Fractal::collection($posts, new PostTransformer()));
    }
}
