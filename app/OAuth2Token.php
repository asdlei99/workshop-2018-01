<?php

namespace App;

use Cantjie\Oauth2\Provider;
use Illuminate\Database\Eloquent\Model;

class OAuth2Token extends Model
{
    protected $table = 'tokens';

    public function createOrSave(array $access_token)
    {
        $user = Provider::createResourceOwnerArray($access_token);

        $existing_token = OAuth2Token::where('username','=',$user['username'])->first();
        if(null === $existing_token){
            $token_to_save = $this;
        }else{
            $token_to_save = $existing_token;
        }

        $token_to_save->username = $user['username'];
        $token_to_save->token_type = $access_token['token_type'];
        $token_to_save->expires_in = $access_token['expires_in'];
        $token_to_save->access_token = $access_token['access_token'];
        $token_to_save->refresh_token = $access_token['refresh_token'];

        try{
            $token_to_save->save();
        }catch (\Exception $e){
            return false;
//            dd($e->getMessage());
        }
        return $token_to_save->id;
    }

}
