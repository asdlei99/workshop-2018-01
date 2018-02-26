<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\ReturnHelper;
use App\OAuth2Token;
use Cantjie\Oauth2\Provider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class IndexController extends Controller
{
    /**
     * @param Request $request
     * @return array|redirect
     */
    public function login(Request $request)
    {
        $provider = new Provider([
                'state' => $request->input('state',url()->previous())
            ]);

        if($request->input('code')){
            if($provider->checkState()){
                $access_token = $provider->getAccessToken($request->input('code'));

                $token = new OAuth2Token();  // 把token存到数据库里
                $token_id = $token->createOrSave($access_token);

                if($token_id){
                    /**
                     * 将token_id和access_token加入cookie
                     */
                    Cookie::queue(Cookie::make('token_id', $token_id, 60));
                    Cookie::queue(Cookie::make('access_token',encrypt($access_token['access_token'],60)));
                    return redirect($request->input('state',config('app.url')));
                    /**
                     *将token_id和access_token添加到url中
                     */
//                    $query = [
//                        'access_token' => encrypt($access_token['access_token']),
//                        'token_id' => $token_id,
//                    ];
//                    $query = http_build_query($query);
//                    return redirect(
//                        $request->input('state',config('app.url'))
//                        .'?'
//                        .$query
//                    );
                    /**
                     * 原始
                     */
//                    return ReturnHelper::returnWithStatus([
//                        'access_token' => encrypt($access_token['access_token']),
//                        'token_id' => $token_id,
//                        'state' => $request->input('state',null),
//                        ]);
                }else{
                    return ReturnHelper::returnWithStatus('认证失败',403);
                }

            }else{
                return ReturnHelper::returnWithStatus('Invalid state',401);
            }
        }else{
            return $provider->redirectToAuthorizationUrl();
        }
    }

    public function index(Request $request)
    {
        echo '<a href="/login">登录</a>';
        echo '</br>';
        echo '<a href="/logout">登出</a>';
        echo '</br>';

        var_dump($_COOKIE) ;
        dd('这是e曈创意工坊首页');
//        $user = (new Provider())->getResourceOwner();
//        if(($pre_page = $user->getPrePage())){
//            return redirect($pre_page);
//        }
//        return $user->getName();
    }

    public function logout()
    {
        Cookie::queue(Cookie::forget('access_token'));
        Cookie::queue(Cookie::forget('token_id'));

        return redirect(config('app.url'));
    }

}
