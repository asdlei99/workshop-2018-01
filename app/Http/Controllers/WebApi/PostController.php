<?php

namespace App\Http\Controllers\WebApi;

use App\Archive;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\ReturnHelper;
use App\Post;
use App\PostArchive;
use App\PostPopularity;
use App\Transformers\PostSimpleDataTransformer;
use App\Transformers\PostTransformer;
use Cyvelnet\Laravel5Fractal\Facades\Fractal;
use Cyvelnet\Laravel5Fractal\Paginators\IlluminateLengthAwarePaginatorAdapter;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use League\Fractal\Resource\Collection;


class PostController extends Controller
{
    public function index(Request $request)
    {
        $cnt = $request->input('cnt',15);
        $paginator = Post::orderBy('id', 'desc')->simplePaginate($cnt);
        $posts = $paginator->getCollection();

        return ReturnHelper::returnWithStatus(
            Fractal::collection($posts,new PostSimpleDataTransformer()),
            200,
            $paginator
        );
    }

    public function show($id)
    {
        $post = Post::find($id);

        if($post === null){
            return ReturnHelper::returnWithStatus('未找到指定文章',2003);
        }

        $post_popularity = $post->getPopularity();
        $post_popularity->view_count = $post_popularity->view_count + 1;
        $post_popularity->save();

        return ReturnHelper::returnWithStatus(Fractal::item($post,new PostTransformer()));
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
            $post->description = substr($request->input('description'),0,200);
        }

        $post_archive = new PostArchive();
        $post_archive->archive_id = $request->input('archive');

        $post_popularity = new PostPopularity();

        try{
            $post->save();

            $post_archive->post_id = $post->id;
            $post_archive->save();

            $post_popularity->post_id = $post->id;
            $post_popularity->save();
        }catch (\Exception $e) {
            return ReturnHelper::returnWithStatus('文章储存失败，请稍后重试', 2002);
        }
        return ReturnHelper::returnWithStatus(Fractal::item($post, new PostTransformer()));
    }

    public function update(StorePostRequest $request, $id)
    {
        $post = Post::find($id);
        if($post === null){
            return ReturnHelper::returnWithStatus('未找到指定文章',2003);
        }

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
            $post->description = substr($request->input('description'),0,200);
        }

        $post_archive = PostArchive::findByPostId($post->id);
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

        $post_archive = PostArchive::findByPostId($id);

        $post_popularity = $post->getPopularity();

        try{
            $post->delete();
            $post_archive->delete();
            $post_popularity->delete();
        }catch (\Exception $e){
            return ReturnHelper::returnWithStatus('文章删除失败，请稍后重试',2002);
        }
        return ReturnHelper::returnWithStatus();
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

        $paginator = Post::whereIn('id',$post_ids)->simplePaginate($cnt);
        $posts =  $paginator->getCollection();
        return ReturnHelper::returnWithStatus(
            Fractal::collection($posts, new PostSimpleDataTransformer()),
            200,
            $paginator
        );
    }

}
