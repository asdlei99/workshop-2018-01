<?php

use Illuminate\Http\Request;

/**
 * 用户
 */
Route::prefix('users')->namespace('WebApi')->group(function(){
    Route::post('/','UserController@create');
    Route::patch('/','UserController@update')->middleware('auth');
    Route::delete('/','UserController@destroy')->middleware('auth');
    Route::post('/avatar','UserController@uploadAvatar')->middleware('auth');

    Route::post('/info','UserController@getSelfInfo')->middleware('auth');
    Route::get('/{username}/info','UserController@getInfo');

});

/**
 * 个人中心
 */
Route::prefix('users')->middleware('auth')->namespace('WebApi')->group(function (){
    Route::post('/favorite','UserController@getFavoritedPost');
    Route::post('/publish','UserController@getPublished');

    Route::post('/messages/comments','UserController@getCommentMessage');
    Route::patch('/messages/comments/{id}','UserController@readCommentMessage');

    Route::post('/messages/likes','UserController@getLikeMessage');
    Route::post('/messages/likes/comments','UserController@getCommentLikeMessage');
    Route::post('/messages/likes/posts','UserController@getPostLikeMessage');
    Route::patch('/messages/likes/{id}','UserController@readLikeMessage');


    Route::post('/messages/system','UserController@getSystemMessage');
    Route::patch('/messages/system/{id}','UserController@readSystemMessage');
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
Route::get('/archives','WebApi\\ArchiveController@show');

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

/**
 * 管理员
 */
Route::post('/admin/message','WebApi\\AdminController@createSystemMessage')->middleware('auth');

/**
 * 搜索
 */
Route::namespace('WebApi')->group(function(){
    Route::get('search/u','SearchController@searchUser');
    Route::get('search/p','SearchController@searchPost');
});


