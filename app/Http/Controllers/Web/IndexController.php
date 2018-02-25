<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\ReturnHelper;
use App\OAuth2Token;
use Cantjie\Oauth2\Provider;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    /**
     * @param Request $request
     * @return array|redirect
     */
    public function login(Request $request)
    {
        $provider = new Provider([
                'state' => $request->input('state',url()->current())
            ]);

        if($request->input('code')){
            if($provider->checkState()){
                $access_token = $provider->getAccessToken($request->input('code'));

                $token = new OAuth2Token();  // 把token存到数据库里
                $token_id = $token->createOrSave($access_token);

                if($token_id){
                    return ReturnHelper::returnWithStatus([
                        'access_token' => encrypt($access_token['access_token']),
                        'token_id' => $token_id,
                        'state' => $request->input('state',null),
                        ]);
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

//        $user = (new Provider())->getResourceOwner();
//        if(($pre_page = $user->getPrePage())){
//            return redirect($pre_page);
//        }
//        return $user->getName();
    }

    public function logout()
    {
        Provider::logout();
//        return redirect('https://account.eeyes.net/logout?redirect='.config('APP_URL'));
    }

}
