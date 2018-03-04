<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CreateSystemMessageRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\SystemMessageText;
use App\Http\ReturnHelper;

class MessageController extends Controller
{
    public function createSystemMessage(CreateSystemMessageRequest $request)
    {
        $system_message = new SystemMessageText();
        $system_message->title = $request->input('title','系统通知');
        $system_message->body = $request->input('body','系统通知正文');
        $system_message->user_group = $request->input('user_group',3);

        try{
            $system_message->save();
        }catch (\Exception $e){
//            return $e->getMessage();
            return ReturnHelper::returnWithStatus('通知发布失败',6002);
        }

        return ReturnHelper::returnWithStatus();
    }
}
