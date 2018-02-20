<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

/**
 * 用户
*/
Route::prefix('users')->namespace('WebApi')->group(function(){
    Route::post('/','UserController@create');
    Route::patch('/','UserController@update')->middleware('auth');
    Route::delete('/','UserController@destroy')->middleware('auth');
    Route::post('/info','UserController@getSelfInfo')->middleware('auth');
    Route::get('/{username}/info','UserController@getInfo');
    Route::post('/publish','UserController@getPublished')->middleware('auth');

    Route::post('/messages/comments','UserController@getCommentMessage')->middleware('auth');
    Route::post('/messages/comments/{id}','UserController@readCommentMessage')->middleware('auth');

    Route::post('/messages/likes','UserController@getLikeMessage')->middleware('auth');
    Route::post('/messages/likes/{id}','UserController@readLikeMessage')->middleware('auth');
});

/**
 * 文章
 */
Route::prefix('posts')->namespace('WebApi')->group(function() {
    Route::get('/home','PostController@index');
    Route::post('/','PostController@store')->middleware('auth');
    Route::get('/{post}','PostController@show');
    Route::patch('/{post}','PostController@update')->middleware('auth');
    Route::delete('/{post}','PostController@destroy')->middleware('auth');
    Route::get('/{post}/comments','CommentController@getPostComments');
});

/**
 * 文章类别
 */
Route::get('/archives/{archive}','WebApi\\PostController@showByArchive');
Route::get('/archives','WebApi\\archiveController@show');

/**
 * 评论
 */
Route::middleware('auth')->prefix('comments')->namespace('WebApi')->group(function (){
    Route::post('/posts/{post}','CommentController@addToPost');
    Route::post('/comments/{comment}','CommentController@addToComment');
    Route::delete('/{comment}','CommentController@destroy');
});

/**
 * 点赞
 */
Route::middleware('auth')->prefix('likes')->namespace('WebApi')->group(function (){
    Route::post('/posts/{post}','LikeController@likePost');
    Route::post('/comments/{comment}','LikeController@likeComment');
});

/**
 * 收藏
 */
Route::post('/favorites/posts/{post}','WebApi\\FavoriteController@favoritePost')->middleware('auth');


