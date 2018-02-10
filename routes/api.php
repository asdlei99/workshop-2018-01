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

Route::prefix('posts')->namespace('WebApi')->group(function() {
    Route::get('/home','PostController@index');
    Route::post('/','PostController@store');
    Route::get('/{post}','PostController@show');
    Route::patch('/{post}','PostController@update');
    Route::delete('/{post}','PostController@destroy');
});

Route::prefix('comments')->namespace('WebApi')->group(function (){
    Route::post('/posts/{post}','CommentController@addToPost');
    Route::post('/comments/{comment}','CommentController@addToComment');
    Route::delete('/comments/{comment}','CommentController@destroy');
});

Route::prefix('users')->namespace('WebApi')->group(function(){
   Route::post('/info','UserController@getSelfInfo');
});

//Route::middleware('session')->namespace('WebApi')->group(function(){
//
//});


