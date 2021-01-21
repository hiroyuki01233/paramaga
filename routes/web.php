<?php

use Illuminate\Support\Facades\Route;

Route::get('/','App\Http\Controllers\IndexController@index');

Route::get('/article/{id}', 'App\Http\Controllers\IndexController@article');
Route::get('/view/{name}', 'App\Http\Controllers\IndexController@view');
Route::get('/preview/{name}', 'App\Http\Controllers\IndexController@preview');
Route::get('/v1/image/publicMangaByFlameNumber', 'App\Http\Controllers\ImageController@publicMangaByFlameNumber');
Route::Resource('/v1/comment', 'App\Http\Controllers\CommentController',['only' => ['index', 'store', 'destroy']]);
Route::Resource('/v1/like', 'App\Http\Controllers\LikeController',['only' => ['store', 'destroy']]);
Route::get('/v1/image/thumbnailPub','App\Http\Controllers\ImageController@thumbnailPublic');
Route::get('/info', 'App\Http\Controllers\IndexController@info');
Route::get('/form', function () {
    return view('form');
});
Route::POST('/form', 'App\Http\Controllers\IndexController@form');

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/manga', 'App\Http\Controllers\MangaController@index');
    Route::get('/create', 'App\Http\Controllers\CreateController@index');
    Route::get('/v1/image/previewManga', 'App\Http\Controllers\ImageController@previewManga');
    Route::get('/v1/image/myMangaThumbnaiAll', 'App\Http\Controllers\ImageController@myMangaThumbnaiAll');
    Route::Resource('/v1/image', 'App\Http\Controllers\ImageController',['except' => ['index', 'create','show']]);
    Route::get('/edit/{id}','App\Http\Controllers\EditController@edit');
});

Route::get('/profile', 'App\Http\Controllers\IndexController@userProfile');

Route::middleware(['auth:sanctum', 'verified'])->get('/user', 'App\Http\Controllers\IndexController@user')->name('user');