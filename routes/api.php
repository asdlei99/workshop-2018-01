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
//    Route::get('/home','PostController@index');
    Route::post('/','PostController@store')->middleware('auth');
//    Route::get('/{post}','PostController@show');
//    Route::patch('/{post}','PostController@update');
//    Route::delete('/{post}','PostController@destroy');
});

Route::prefix('comments')->namespace('WebApi')->group(function (){
    Route::post('/posts/{post}','CommentController@addToPost');
    Route::post('/comments/{comment}','CommentController@addToComment');
    Route::delete('/comments/{comment}','CommentController@destroy');
});

Route::prefix('users')->namespace('WebApi')->group(function(){
    Route::post('/','UserController@create');
    Route::patch('/','UserController@update')->middleware('auth');
    Route::delete('/','UserController@destroy')->middleware('auth');
    Route::post('/info','UserController@getSelfInfo')->middleware('auth');
    Route::get('/{username}/info','UserController@getInfo');
});



