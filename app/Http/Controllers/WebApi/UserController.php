<?php

namespace App\Http\Controllers\WebApi;

use App\CommentLikeMessageControl;
use App\CommentMessageControl;
use App\Favorite;
use App\Http\Requests\CreateUser;
use App\Http\Requests\uploadAvatarRequest;
use App\Http\ReturnHelper;
use App\Post;
use App\PostLikeMessageControl;
use App\SystemMessageControl;
use App\SystemMessageText;
use App\Transformers\MessageControlTransformer;
use App\Transformers\PostTransformer;
use App\Transformers\UserOtherTransformer;
use App\Transformers\UserSelfTransformer;
use Cantjie\Oauth2\Provider;
use App\Http\Controllers\Controller;
use App\User;
use Carbon\Carbon;
use Cyvelnet\Laravel5Fractal\Facades\Fractal;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Http\Request;
use App\OAuth2Token;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    //仅在创建新用户中使用了。
    protected function __checkToken($request){
        $oauth2token = OAuth2Token::find($request->input('token_id',0));

        if(! $oauth2token){
            $response = response()->json(
                ReturnHelper::returnWithStatus('token_id错误',1002)
            );
            return $response;
        }

        $access_token = decrypt($request->input('access_token'));

        if($oauth2token->access_token !== $access_token){
            $response = response()->json(
                ReturnHelper::returnWithStatus('token_id与token不符，请尝试重新登录',1003)
            );
            return $response;
        }
        return true;
    }

    //新建用户
    public function create(Request $request)
    {
        //检查token是否有效
        $token_valid = $this->__checkToken($request);
        if($token_valid !== true){
            return $token_valid;
        }
        //获得用户CAS信息
        $access_token = decrypt($request->input('access_token'));
        $resourceOwner = Provider::createResourceOwnerArray($access_token);

        //新建用户
        $user = new User();
        $user->username = $resourceOwner['username'];
        $user->email = $request->input('email');
        $user->nickname = $request->input('nickname');
        $user->phone = $request->input('phone',null);
        $user->qq = $request->input('qq',null);
        $user->signature = $request->input('signature',null);
        if($request->input('phone_access')){
            $user->phone_access = $request->input('phone_access');
        }
        if($request->input('email_access')){
            $user->email_access = $request->input('email_access');
        }
        if($request->input('qq_access')){
            $user->qq_access = $request->input('qq_access');
        }

        try{
            $user->save();
        }catch (\Exception $e){
//            return $e->getMessage();
            return ReturnHelper::returnWithStatus('注册失败',1004);
        }

        return ReturnHelper::returnWithStatus(Fractal::item($user,new UserSelfTransformer()));

    }

    //完善用户信息
    public function update(Request $request)
    {
        $user = session('user');
        $user->email = $request->input('email',$user->email);
        $user->nickname = $request->input('nickname',$user->nickname);
        $user->phone = $request->input('phone',$user->phone);
        $user->qq = $request->input('qq',$user->qq);
        $user->signature = $request->input('signature',$user->qq);
//        $user->head_img =   //TODO  头像功能
        $user->phone_access = $request->input('phone_access',$user->phone_access);
        $user->email_access = $request->input('email_access',$user->email_access);
        $user->qq_access = $request->input('qq_access',$user->qq_access);
        try{
            $user->save();
        }catch (\Exception $e){
//            return $e->getMessage();
            return ReturnHelper::returnWithStatus('修改失败',1004);
        }

        return ReturnHelper::returnWithStatus(Fractal::item($user,new UserSelfTransformer()));
    }

    public function uploadAvatar(uploadAvatarRequest $request)
    {
        $user = session('user');

        $path = $request->head_img->store('avatars');
        $path = '/storage/'.$path;

        $old_path = $user->head_img;
        if($old_path !== null){
            $old_path = substr($old_path,9);
            Storage::delete($old_path);
        }

        $user->head_img = $path;

        try{
            $user->save();
        }catch (\Exception $e){
            return ReturnHelper::returnWithStatus('头像上传失败',1007);
        }

        return ReturnHelper::returnWithStatus(Fractal::item($user,new UserSelfTransformer()));
    }

    //注销用户
    public function destroy()
    {
        $user = session('user');

        try{
            $user->delete();
        }catch (\Exception $e){
            return ReturnHelper::returnWithStatus('注销失败',1005);
        }

        return ReturnHelper::returnWithStatus();
    }

    //获得当前用户信息
    public function getSelfInfo()
    {
        $user = session('user');
        return ReturnHelper::returnWithStatus(Fractal::item($user,new UserSelfTransformer()));
    }

    //获得某用户信息
    public function getInfo($username)
    {
        $user = User::getUserByUsername($username);
        return ReturnHelper::returnWithStatus(Fractal::item($user,new UserOtherTransformer()));
    }


    //个人中心——获取已发布文章列表
    public function getPublished(Request $request)
    {
        $cnt = $request->input('cnt',15);
        $user = session('user');
        $paginator = $user->posts()->orderBy('id','desc')->simplePaginate($cnt);
        $published = $paginator->getCollection();

        foreach($published as $key => $post){
            $post->index = $key;
        }

        return ReturnHelper::returnWithStatus(Fractal::collection($published,new PostTransformer()),200,$paginator);
    }

    //个人中心——获得他发布的文章
    public function getOthersPublished(Request $request, $username)
    {
        $cnt = $request->query('cnt',15);

        $user = User::getUserByUsername($username);
        if($user === null){
            return ReturnHelper::returnWithStatus('未找到指定用户',1008);
        }

        $paginator = $user->posts()->orderBy('id','desc')->simplePaginate($cnt);
        $published = $paginator->getCollection();

        foreach ($published as $key => $post){
            $post->index = $key;
        }

        return ReturnHelper::returnWithStatus(Fractal::collection($published,new PostTransformer()),200,$paginator);

    }

    //个人中心——获得评论自己的消息
    public function getCommentMessage(Request $request)
    {
        $cnt = $request->input('cnt',15);

        $paginator = CommentMessageControl::userId(session('user'))->orderBy('id','desc')->simplepaginate($cnt);
        $messages = $paginator->getCollection();

//        $messages = CommentMessageControl::getByUserId(session('user'));
        return ReturnHelper::returnWithStatus(
            Fractal::includes('comment')->collection($messages,new MessageControlTransformer()),
            200,
            $paginator
        );
    }
    
    //个人中心——将评论自己的消息标记为已读
    public function readCommentMessage($id)
    {
        $message = CommentMessageControl::find($id);
        if($message === null){
            return ReturnHelper::returnWithStatus('未找到该记录',5001);
        }
        $message->has_read = 1;
        try{
            $message->save();
        }catch (\Exception $e){
            return ReturnHelper::returnWithStatus('标记已读失败',5002);
        }
        return ReturnHelper::returnWithStatus();
    }

    //个人中心——获得给自己点的赞
    public function getLikeMessage(Request $request)
    {
        //todo 不知如何实现分页功能
        $user_id = session('user')->id;
        $comment_likes = CommentLikeMessageControl::userId($user_id)->orderBy('id','desc')->get();
        $post_likes = PostLikeMessageControl::userId($user_id)->orderBy('id','desc')->get();

        $j = count($comment_likes)- 1;
        $k = count($post_likes) - 1;
        $likes = [];
        for ($i = 0; ($k>=0 && $j>=0);$i++){
            if($comment_likes[$j]->created_at > $post_likes[$k]->created_at){
                $likes[$i] = $comment_likes[$j];
                $j--;
            }else{
                $likes[$i] = $post_likes[$k];
                $k--;
            }
        }

        if($k < 0){
            for(; $j >= 0; $i++,$j--){
                $likes[$i] = $comment_likes[$j];
            }
        }else{
            for(; $k >= 0;$i++,$k--){
                $likes[$j] = $post_likes[$k];
            }
        }

        return ReturnHelper::returnWithStatus(Fractal::collection($likes,new MessageControlTransformer()));
    }

    //个人中心——将给自己点赞的消息标记为已读
    public function readLikeMessage(Request $request, $id)
    {
        $like_type = $request->input('like_type','comment');
        if($like_type === 'comment'){
            $like_message = CommentLikeMessageControl::find($id);
        }elseif($like_type === 'post'){
            $like_message = PostLikeMessageControl::find($id);
        }

        if($like_message === null){
            return ReturnHelper::returnWithStatus('未找到该记录',5001);
        }

        $like_message->has_read = 1;

        try{
            $like_message->save();
        }catch (\Exception $e){
            return ReturnHelper::returnWithStatus('标记已读失败',5002);
        }

        return ReturnHelper::returnWithStatus();

    }

    //个人中心——获取系统信息列表
    public function getSystemMessage()
    {
        $user = session('user');

        //找到在text表中发布但没添加到control表中的系统消息
        $message_texts = SystemMessageText::UserGroupMoreThan($user->user_group)->get()->toArray();
        $message_text_ids = array_map(function($message_text){return $message_text['id'];},$message_texts);
        $message_controls = SystemMessageControl::Ids($message_text_ids)->get()->toArray();
        $message_control_message_ids = array_map(function($message_control){return $message_control['message_id'];},$message_controls);

        //如果有上述消息，就创建新的SystemMessageControl
        $not_added = array_diff($message_text_ids,$message_control_message_ids);
        foreach ($not_added as $message_id){
            $message_control = new SystemMessageControl();
            $message_control->user_id = $user->id;
            $message_control->message_id = $message_id;
            $message_control->save();
            $message_controls[] = $message_control;
        }

        //返回SystemMessage
        $messages = [];
        $i = 0;
        foreach ($message_controls as $message_control){
            $message_text = SystemMessageText::find($message_control['message_id']);
            $messages[$i] = [
                'id' => $message_control['id'],
                'title' => $message_text['title'],
                'body' => $message_text['body'],
                'created_at' => Carbon::parse($message_text['created_at'])->format("Y-m-d h:i:s"),
                'has_read' => $message_control['has_read'],
            ];
        }

        return ReturnHelper::returnWithStatus($messages);
    }
    
    //个人中心——将系统信息标为已读
    public function readSystemMessage($id)
    {
        $message = SystemMessageControl::find($id);
        if($message === null){
            return ReturnHelper::returnWithStatus('未找到该记录',5001);
        }
        $message->has_read = 1;
        try{
            $message->save();
        }catch (\Exception $e){
            return ReturnHelper::returnWithStatus('标记已读失败',5002);
        }
        return ReturnHelper::returnWithStatus();
    }


    //个人中心——获得 自己的文章收到的赞的消息
    public function getPostLikeMessage(Request $request)
    {
        $cnt = $request->input('cnt',15);

        $paginator = PostLikeMessageControl::userId(session('user'))->orderBy('id','desc')->simplePaginate($cnt);
        $post_like_messages = $paginator->getCollection();

        return ReturnHelper::returnWithStatus(
            Fractal::includes('post')->collection($post_like_messages,new MessageControlTransformer()),
            200,
            $paginator
        );
    }

    //个人中心——获得 自己的评论收到的赞的消息
    public function getCommentLikeMessage(Request $request)
    {
        $cnt = $request->input('cnt',15);

        $paginator = CommentLikeMessageControl::userId(session('user'))->orderBy('id','desc')->simplePaginate($cnt);
        $comment_like_messages = $paginator->getCollection();

        return ReturnHelper::returnWithStatus(
            Fractal::includes('comment')->collection($comment_like_messages,new MessageControlTransformer()),
            200,
            $paginator
        );

    }
    
    //个人中心——我的收藏
    public function getFavoritedPost(Request $request)
    {
        $cnt = $request->input('cnt',15);

        $user = session('user');

        $post_ids = Favorite::userId($user)->pluck('post_id')->toArray();
        $paginator = Post::postIds($post_ids)->orderBy('id','desc')->simplePaginate();
        $posts = $paginator->getCollection();

        return ReturnHelper::returnWithStatus(
            Fractal::collection($posts, new PostTransformer()),
            200,
            $paginator
        );
    }

}

