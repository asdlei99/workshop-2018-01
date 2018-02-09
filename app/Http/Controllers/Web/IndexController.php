<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Cantjie\Oauth2\Provider;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function login()
    {
        $query = http_build_query([
            'client_id' => env('OAUTH_CLIENT_ID'),
            'redirect_uri' => env('OAUTH_CLIENT_URI'),
            'response_type' => 'code',
            'scope' => 'info-username.read info-user_id.read info-name.read',
            'state' => 'index',
        ]);

        return redirect('https://account.eeyes.net/oauth/authorize?'.$query);
    }

    public function index()
    {
        $user = (new Provider())->getResourceOwner();
        if(($pre_page = $user->getPrePage())){
            return redirect($pre_page);
        }
        return $user->getName();
    }

    public function logout()
    {
        Provider::logout();
    }


}
