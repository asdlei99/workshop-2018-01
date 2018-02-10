<?php

namespace App\Http\Controllers\WebApi;

use App\Http\Requests\CreateUser;
use App\Http\ReturnHelper;
use Cantjie\Oauth2\Provider;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function create(CreateUser $request)
    {
//        $user = $this->checkAuth();
//        if($user !== null){
//            return ReturnHelper::returnWithStatus(['errors'=>'您已登录，无需注册']);
//        }
//
//        $resourceOwner = (new Provider())->getResourceOwner();
//
//        if(User::getUserByUsername($resourceOwner->getUsername())){
//            return ReturnHelper::returnWithStatus(['errors'=>'该账号已注册，请登录']);
//        }
//        $user = new User();
//        $user->username = $resourceOwner->getUsername();
//        $user->email = $request->input('email');
//        $user->nickname = $request->input('nickname');
//        $user->phone = $request->input('phone',null);
//        $user->qq = $request->input('qq',null);
//
//        try{
//            $user->save();
//        }catch (\Exception $e){
//            return ReturnHelper::returnWithStatus(['errors'=>$e->getMessage()],Response::HTTP_BAD_REQUEST,'ERROR');
//        }
//
//        return ReturnHelper::returnWithStatus();
        
    }

    public function index()
    {
        
    }

    public function login()
    {

    }

    public function getSelfInfo(Request $request)
    {
        $access_token = $this->checkAuth($request);
        if(false === $access_token){
            return ReturnHelper::returnWithStatus();
        }else{
            $user = Provider::createResourceOwnerArray($access_token);
            return ReturnHelper::returnWithStatus($user);
        }
    }

}
