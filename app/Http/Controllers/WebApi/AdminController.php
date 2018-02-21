<?php

namespace App\Http\Controllers\WebApi;

use App\Http\Requests\CreateSystemMessageRequest;
use App\Http\ReturnHelper;
use App\SystemMessageText;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    public function createSystemMessage(CreateSystemMessageRequest $request)
    {
        $user = session('user');
        if($user->user_group >= 3){
            ReturnHelper::returnWithStatus('您没有发表系统通知的权限',6001);
        }

        $system_message = new SystemMessageText();
        $system_message->title = $request->input('title','系统通知');
        $system_message->body = $request->input('body','系统通知正文');
        $system_message->user_group = $request->input('user_group',3);

        try{
            $system_message->save();
        }catch (\Exception $e){
            return ReturnHelper::returnWithStatus('通知发布失败',6003);
        }

        return ReturnHelper::returnWithStatus();

    }

}
