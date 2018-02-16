<?php

namespace App\Http\Controllers\WebApi;

use App\Http\Requests\CreateUser;
use App\Http\ReturnHelper;
use App\Transformers\PostTransformer;
use App\Transformers\UserOtherTransformer;
use App\Transformers\UserSelfTransformer;
use Cantjie\Oauth2\Provider;
use App\Http\Controllers\Controller;
use App\User;
use Cyvelnet\Laravel5Fractal\Facades\Fractal;
use Illuminate\Http\Request;
use App\OAuth2Token;

class UserController extends Controller
{
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

    public function getPublished()
    {
        $user = session('user');
//        $published = $user->getPosts();
        $published = $user->posts()->simplePaginate();
        return ReturnHelper::returnWithStatus(Fractal::collection($published,new PostTransformer()));
    }
}
