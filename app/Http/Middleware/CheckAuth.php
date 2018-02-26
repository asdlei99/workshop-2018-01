<?php

namespace App\Http\Middleware;

use App\Http\ReturnHelper;
use App\User;
use Cantjie\Oauth2\Provider;
use Closure;
use App\OAuth2Token;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class CheckAuth
{
    /**
     * Handle an incoming request.这里的状态码请参照文档
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $user_group = null)
    {
//        $token_id = Cookie::get('token_id');
//        $access_token = Cookie::get('access_token');
//        dd($token_id);
        $oauth2token = OAuth2Token::find($request->input('token_id',0));

        if(! $oauth2token){
            $response = response()->json(
                ReturnHelper::returnWithStatus('token_id错误',1002)
            );
            return $response;
        }
        //todo 也许这里也可以检查是否过期
        try{
            $access_token = decrypt($request->input('access_token'));
        }catch (\Exception $e){
            $response = response()->json(
                ReturnHelper::returnWithStatus('access_token错误，无法解析',1006)
            );
            return $response;
        }


        if($oauth2token->access_token !== $access_token){
            $response = response()->json(
                ReturnHelper::returnWithStatus('token_id与token不符，请尝试重新登录',1003)
            );
            return $response;
        }

        $resourceOwner = Provider::createResourceOwnerArray($access_token);
        $user = User::getUserByUsername($resourceOwner['username']);

        if(null === $user){
            $response = response()->json(
                ReturnHelper::returnWithStatus('第一次登陆，请完善信息',1001)
            );
            return $response;
        }

        //查看是否为管理员等
        if($user_group !== null){
            if($user->user_group > $user_group){
                $response = response()->json(
                    ReturnHelper::returnWithStatus('权限不足',6000)
                );
                return $response;
            }
        }

        session()->flash('user',$user);

        return $next($request);
    }
}
