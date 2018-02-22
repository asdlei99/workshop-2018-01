<?php

namespace App\Http\Controllers\WebApi;

use App\Http\ReturnHelper;
use App\Post;
use App\Transformers\PostTransformer;
use App\Transformers\UserOtherTransformer;
use App\User;
use Cyvelnet\Laravel5Fractal\Facades\Fractal;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SearchController extends Controller
{
    public function searchUser(Request $request)
    {
        $cnt = $request->query('cnt',15);
        $word = $request->query('wd');
        $paginator = User::search($word, ['username','nickname'])->orderByDate()->simplePaginate($cnt);
        $users = $paginator->getCollection();

        return ReturnHelper::returnWithStatus(
            Fractal::collection($users, new UserOtherTransformer()),
            200,
            $paginator
        );
    }

    public function searchPost(Request $request)
    {
        $cnt = $request->query('cnt',15);
        $word = $request->query('wd');
        $paginator = Post::search($word,['title','description','body'])->orderByDate()->simplePaginate($cnt);
        $posts = $paginator->getCollection();

        return ReturnHelper::returnWithStatus(
            Fractal::collection($posts, new PostTransformer()),
            200,
            $paginator
        );
    }
}
