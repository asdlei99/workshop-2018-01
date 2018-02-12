<?php

namespace App\Http\Middleware;

use App\Http\ReturnHelper;
use App\User;
use Cantjie\Oauth2\Provider;
use Closure;
use App\OAuth2Token;
use Illuminate\Http\Request;

class CheckAuth
{
    /**
     * Handle an incoming request.这里的状态码请参照文档
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $oauth2token = OAuth2Token::find($request->input('token_id',0));

        if(! $oauth2token){
            $response = response()->json(
                ReturnHelper::returnWithStatus('token_id错误',1002)
            );
            return $response;
        }
        //todo 也许这里也可以检查是否过期

        $access_token = decrypt($request->input('access_token'));

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

        session()->flash('user',$user);

        return $next($request);
    }
}
