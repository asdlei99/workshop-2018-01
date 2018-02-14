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
        if(Post::find($id) === null){
            return ReturnHelper::returnWithStatus('未找到指定文章',2003);
        }

        $user_id = session('user')->id;
        $favorite = Favorite::where('user_id',$user_id)->where('post_id',$id)->first();
        if($favorite !== null){
            try{
                $favorite->delete();
            }catch (\Exception $e){
                return ReturnHelper::returnWithStatus('取消收藏失败',4004);
            }
            return ReturnHelper::returnWithStatus('取消收藏成功',4104);
        }else{
            $favorite = new Favorite();
            $favorite->user_id = $user_id;
            $favorite->post_id = $id;
            $favorite->created_at = Carbon::now();
            try{
                $favorite->save();
            }catch (\Exception $e ){
                return ReturnHelper::returnWithStatus('收藏失败',4003);
            }
            return ReturnHelper::returnWithStatus('收藏成功',4103);
        }

    }
}