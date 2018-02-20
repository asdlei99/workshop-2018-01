<?php

namespace App\Http\Controllers\WebApi;

use App\CommentLikeMessageControl;
use App\CommentMessageControl;
use App\Http\Requests\CreateUser;
use App\Http\ReturnHelper;
use App\PostLikeMessageControl;
use App\Providers\EventServiceProvider;
use App\Transformers\MessageControlTransformer;
use App\Transformers\PostTransformer;
use App\Transformers\UserOtherTransformer;
use App\Transformers\UserSelfTransformer;
use Cantjie\Oauth2\Provider;
use App\Http\Controllers\Controller;
use App\User;
use Carbon\Carbon;
use Cyvelnet\Laravel5Fractal\Facades\Fractal;
use Illuminate\Http\Request;
use App\OAuth2Token;

class UserController extends Controller
{
    //仅在创建新用户中使用了。
    protected function __checkToken($request){
        $oauth2token = OAuth2Token::find($request->input('token_id',0));

        if(! $oauth2token){
            $response = response()->json(
                ReturnHelper::returnWithStatus(['errors'=>'token_id错误'],1002,'Unauthorized')
            );
            return $response;
        }

        $access_token = decrypt($request->input('access_token'));

        if($oauth2token->access_token !== $access_token){
            $response = response()->json(
                ReturnHelper::returnWithStatus(['errors'=>'token_id与token不符，请尝试重新登录'],1003,'Unauthorized')
            );
            return $response;
        }
        return true;
    }

    //新建用户
    public function create(Request $request)
    {
        //检查token是否有效
        $token_valid = $this->__checkToken($request);
        if($token_valid !== true){
            return $token_valid;
        }
        //获得用户CAS信息
        $access_token = decrypt($request->input('access_token'));
        $resourceOwner = Provider::createResourceOwnerArray($access_token);

        //新建用户
        $user = new User();
        $user->username = $resourceOwner['username'];
        $user->email = $request->input('email');
        $user->nickname = $request->input('nickname');
        $user->phone = $request->input('phone',null);
        $user->qq = $request->input('qq',null);
        $user->signature = $request->input('signature',null);
//        $user->head_img =   //TODO  头像功能
        if($request->input('phone_access')){
            $user->phone_access = $request->input('phone_access');
        }
        if($request->input('email_access')){
            $user->email_access = $request->input('email_access');
        }
        if($request->input('qq_access')){
            $user->qq_access = $request->input('qq_access');
        }

        try{
            $user->save();
        }catch (\Exception $e){
//            return $e->getMessage();
            return ReturnHelper::returnWithStatus(['errors'=>'注册失败'],1004,'修改用户信息失败');
        }

        return ReturnHelper::returnWithStatus(Fractal::item($user,new UserSelfTransformer()));

    }

    //完善用户信息
    public function update(Request $request)
    {
        $user = session('user');
        $user->email = $request->input('email',$user->email);
        $user->nickname = $request->input('nickname',$user->nickname);
        $user->phone = $request->input('phone',$user->phone);
        $user->qq = $request->input('qq',$user->qq);
        $user->signature = $request->input('signature',$user->qq);
//        $user->head_img =   //TODO  头像功能
        $user->phone_access = $request->input('phone_access',$user->phone_access);
        $user->email_access = $request->input('email_access',$user->email_access);
        $user->qq_access = $request->input('qq_access',$user->qq_access);
        try{
            $user->save();
        }catch (\Exception $e){
//            return $e->getMessage();
            return ReturnHelper::returnWithStatus(['errors'=>'修改失败'],1004,'修改用户信息失败');
        }

        return ReturnHelper::returnWithStatus(Fractal::item($user,new UserSelfTransformer()));
    }

    //注销用户
    public function destroy()
    {
        $user = session('user');

        try{
            $user->delete();
        }catch (\Exception $e){
            return ReturnHelper::returnWithStatus(['errors'=>'注销失败'],1005,'注销失败');
        }

        return ReturnHelper::returnWithStatus();
    }

    //获得当前用户信息
    public function getSelfInfo()
    {
        $user = session('user');
        return ReturnHelper::returnWithStatus(Fractal::item($user,new UserSelfTransformer()));
    }

    //获得某用户信息
    public function getInfo($username)
    {
        $user = User::getUserByUsername($username);
        return ReturnHelper::returnWithStatus(Fractal::item($user,new UserOtherTransformer()));
    }

    //获取已发布文章列表
    public function getPublished()
    {
        $user = session('user');
//        $published = $user->getPosts();
        $published = $user->posts()->simplePaginate();
        return ReturnHelper::returnWithStatus(Fractal::collection($published,new PostTransformer()));
    }


    //个人中心——获得评论自己的消息
    public function getCommentMessage()
    {
        $messages = CommentMessageControl::getByUserId(session('user'));
        return ReturnHelper::returnWithStatus(Fractal::collection($messages,new MessageControlTransformer()));
    }
    
    //个人中心——将评论自己的消息标记为已读
    public function readCommentMessage($id)
    {
        $message = CommentMessageControl::find($id);
        if($message === null){
            return ReturnHelper::returnWithStatus('未找到该记录',5001);
        }
        $message->has_read = 1;
        try{
            $message->save();
        }catch (\Exception $e){
            return ReturnHelper::returnWithStatus('标记已读失败',5002);
        }
        return ReturnHelper::returnWithStatus();
    }

    //个人中心——获得给自己点的赞
    public function getLikeMessage()
    {
        $user_id = session('user')->id;
        $comment_likes = CommentLikeMessageControl::getByUserId($user_id);
        $post_likes = PostLikeMessageControl::getByUserId($user_id);

        $j = count($comment_likes)- 1;
        $k = count($post_likes) - 1;
        $likes = [];
        for ($i = 0; ($k>=0 && $j>=0);$i++){
            if($comment_likes[$j]->created_at > $post_likes[$k]->created_at){
                $likes[$i] = $comment_likes[$j];
                $j--;
            }else{
                $likes[$i] = $post_likes[$k];
                $k--;
            }
        }

        if($k < 0){
            for(; $j >= 0; $i++,$j--){
                $likes[$i] = $comment_likes[$j];
            }
        }else{
            for(; $k >= 0;$i++,$k--){
                $likes[$j] = $post_likes[$k];
            }
        }

        return ReturnHelper::returnWithStatus(Fractal::collection($likes,new MessageControlTransformer()));
    }

    //个人中心——将给自己点赞的消息标记为已读
    public function readLikeMessage($request, $id)
    {
        $like_type = $request->input('like_type','comment');
        if($like_type === 'comment'){
            $like_message = CommentLikeMessageControl::find($id);
        }elseif($like_type === 'post'){
            $like_message = PostLikeMessageControl::find($id);
        }

        if($like_message === null){
            return ReturnHelper::returnWithStatus('未找到该记录',5001);
        }

        try{
            $like_message->save();
        }catch (\Exception $e){
            return ReturnHelper::returnWithStatus('标记已读失败',5002);
        }

        return ReturnHelper::returnWithStatus();

    }
    
}
