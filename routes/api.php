<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Models\Post;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



// auth
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    // auth logout
    Route::post('logout', [AuthController::class, 'logout']);
    // posts
    Route::apiResource('posts', PostController::class);
    // GET|HEAD        api/posts ............................................. posts.index › PostController@index  
    // POST            api/posts ............................................. posts.store › PostController@store  
    // GET|HEAD        api/posts/{post} ........................................ posts.show › PostController@show  
    // PUT|PATCH       api/posts/{post} .................................... posts.update › PostController@update  
    // DELETE          api/posts/{post} .................................. posts.destroy › PostController@destroy 
});
