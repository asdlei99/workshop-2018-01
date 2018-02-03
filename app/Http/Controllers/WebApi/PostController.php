<?php

namespace App\Http\Controllers\WebApi;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\ReturnHelpers;
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
        $cnt = \request('cnt') ? \request('cnt'): 15;
        $posts = Post::take($cnt)->get();

        $data = (new Manager())
            ->parseIncludes('user')
            ->createData(new Collection($posts,new PostTransformer()))
            ->toArray();

        return ReturnHelpers::returnWithStatus($data['data']);
//        return Fractal::includes('user')->collection($posts,new PostTransformer());

    }

//    public function show(Post $post)
    public function show($id)
    {
        $post = Post::find($id);
        if($post === null){
            return ReturnHelpers::returnWithStatus(null,Response::HTTP_NOT_FOUND,'Not Found');
        }

        $data = (new Manager())
            ->parseIncludes('user')
            ->createData(new Item($post,new PostTransformer()))
            ->toArray();

        return ReturnHelpers::returnWithStatus($data['data']);
//        return Fractal::includes('user')->item($post,new PostTransformer());
    }

    public function store(StorePostRequest $request)
    {
        //TODO 判断是否登录

        $post = new Post();
        $post->title = $request->title;
        $post->description = $request->description;
//        $post->user_id = $request->user()->id;
        $post->user_id = $request->user_id;
        $post->body = $request->body;
        $post->anonymous = $request->anonymous;

        $post->save();

        $data = (new Manager())
            ->parseIncludes('user')
            ->createData(new Item($post,new PostTransformer()))
            ->toArray();

        return ReturnHelpers::returnWithStatus($data['data']);
//        return Fractal::includes('user')->item($post, new PostTransformer());
    }

    public function update(Request $request, $id)
    {
        $post = Post::find($id);
        if($post === null){
            return ReturnHelpers::returnWithStatus(null,Response::HTTP_NOT_FOUND,'Not Found');
        }

        //TODO 需要判断是否认证通过

       if($request->isMethod('patch')){
           $post->title = $request->get('title',$post->title);
           $post->description = $request->get('description',$post->description);
           $post->body = $request->get('body',$post->body);
           $post->anonymous = $request->get('anonymous',$post->anonymous);
       }
       $post->save();

        $data = (new Manager())
            ->parseIncludes('user')
            ->createData(new Item($post,new PostTransformer()))
            ->toArray();
        return ReturnHelpers::returnWithStatus($data['data']);
//       return Fractal::includes('user')->item($post, new PostTransformer());
    }

//    public function destroy(Post $post)
    public function destroy($id)
    {
        //TODO 判断是否认证通过

        $post = Post::find($id);
        if($post === null){
            return ReturnHelpers::returnWithStatus(null,Response::HTTP_NOT_FOUND,'Not Found');
        }
        try{
            $post->delete();
        }catch (\Exception $e){
            return ReturnHelpers::returnWithStatus(null,Response::HTTP_PRECONDITION_FAILED,'Deleted Failed');
//            return $e->getMessage();
        }

        return ReturnHelpers::returnWithStatus();

    }
}
