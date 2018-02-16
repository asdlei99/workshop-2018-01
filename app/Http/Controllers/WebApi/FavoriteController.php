<?php

namespace App\Http\Controllers\WebApi;

use App\Favorite;
use App\Http\ReturnHelper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Post;
use App\Http\Controllers\Controller;

class FavoriteController extends Controller
{
    public function favoritePost($id)
    {
        $post = Post::find($id);
        if($post === null){
            return ReturnHelper::returnWithStatus('未找到指定文章',2003);
        }
        $user_id = session('user')->id;
        $favorite = Favorite::where('user_id',$user_id)->where('post_id',$id)->first();
        $post_popularity = $post->getPopularity();

        if($favorite !== null){
            $post_popularity->favorite_count = $post_popularity->favorite_count - 1;
            try{
                $favorite->delete();
                $post_popularity->save();
            }catch (\Exception $e){
                return ReturnHelper::returnWithStatus('取消收藏失败',4004);
            }
            return ReturnHelper::returnWithStatus('取消收藏成功',4104);
        }else{
            $favorite = new Favorite();
            $favorite->user_id = $user_id;
            $favorite->post_id = $id;
            $favorite->created_at = Carbon::now();
            $post_popularity->favorite_count = $post_popularity->favorite_count + 1;
            try{
                $post_popularity->save();
                $favorite->save();
            }catch (\Exception $e){
                return ReturnHelper::returnWithStatus('收藏失败',4003);
            }
            return ReturnHelper::returnWithStatus('收藏成功',4103);
        }

    }
}