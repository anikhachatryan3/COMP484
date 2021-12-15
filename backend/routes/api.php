<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register2', 'App\Http\Controllers\AuthController@register2');
Route::post('/login2', 'App\Http\Controllers\AuthController@login2');
Route::get('/posts', 'App\Http\Controllers\PostController@getAllPosts');
Route::get('/{user}/posts', 'App\Http\Controllers\PostController@getMyPosts');
Route::post('/create-post', 'App\Http\Controllers\PostController@createPost');
Route::delete('/{user}/posts/{post}', 'App\Http\Controllers\PostController@deletePost');
Route::post('/create-comment', 'App\Http\Controllers\CommentController@createComment');
Route::delete('/{user}/comments/{comment}', 'App\Http\Controllers\CommentController@deleteComment');