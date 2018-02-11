<?php

namespace App\Http\Controllers\WebApi;

use App\Http\Requests\CreateUser;
use App\Http\ReturnHelper;
use App\Transformers\UserSelfTransformer;
use Cantjie\Oauth2\Provider;
use App\Http\Controllers\Controller;
use App\User;
use Cyvelnet\Laravel5Fractal\Facades\Fractal;
use Illuminate\Http\Request;
use App\OAuth2Token;

class UserController extends Controller
{
    protected function checkToken($request){
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

    public function create(CreateUser $request)
    {
        //检查token是否有效
        $token_valid = $this->checkToken($request);
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
//        $user->head_img =   //TODO  头像功能
        if($request->has('phone_access')){
            $user->phone_access = $request->input('phone_access');
        }
        if($request->has('email_access')){
            $user->email_access = $request->input('email_access');
        }
        if($request->has('qq_access')){
            $user->qq_access = $request->input('qq_access');
        }

        try{
            $user->save();
        }catch (\Exception $e){
//            return $e->getMessage();
            return ReturnHelper::returnWithStatus(['errors'=>'注册失败'],1004,'ERROR');
        }

        return ReturnHelper::returnWithStatus($user->username);
        
    }

    public function index()
    {
        
    }


    public function getSelfInfo()
    {
        $user = session('user');
        return ReturnHelper::returnWithStatus(Fractal::item($user,new UserSelfTransformer()));
    }

}
