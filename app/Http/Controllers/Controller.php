<?php

namespace App\Http\Controllers;

use App\OAuth2Token;
use App\User;
use Cantjie\Oauth2\Provider;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @param Request $request
     * @return bool|string false|token
     */
    public function checkAuth(Request $request)
    {
        $oauth2token = OAuth2Token::find($request->input('token_id',0));

        if(! $oauth2token){
            return false;
        }

        //todo 也许这里也可以检查是否过期
        $access_token = decrypt($request->input('access_token'));
        if($oauth2token->access_token !== $access_token){
            return false;
        }

        return $access_token;
    }

}
