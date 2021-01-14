<?php

use Illuminate\Support\Facades\Route;

Route::get('/','App\Http\Controllers\IndexController@index');

Route::get('/view/{name}', 'App\Http\Controllers\IndexController@view');
Route::get('/preview/{name}', 'App\Http\Controllers\IndexController@preview');
Route::get('/v1/image/publicMangaByFlameNumber', 'App\Http\Controllers\ImageController@publicMangaByFlameNumber');
Route::Resource('/v1/comment', 'App\Http\Controllers\CommentController');
Route::Resource('/v1/like', 'App\Http\Controllers\LikeController');

Route::middleware(['auth:sanctum', 'verified'])->get('save',function(){
    return view('save');
});

Route::get('/v1/image/thumbnailPub','App\Http\Controllers\ImageController@thumbnailPublic');

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/manga', 'App\Http\Controllers\MangaController@index');
    Route::get('/create', 'App\Http\Controllers\CreateController@index');
    Route::get('/v1/image/previewManga', 'App\Http\Controllers\ImageController@previewManga');
    Route::get('/v1/image/myMangaThumbnaiAll', 'App\Http\Controllers\ImageController@myMangaThumbnaiAll');
    Route::Resource('/v1/image', 'App\Http\Controllers\ImageController');
    Route::get('/edit/{id}','App\Http\Controllers\EditController@edit');
    Route::get('info','App\Http\Controllers\IndexController@info');
});

Route::middleware(['auth:sanctum', 'verified'])->get('/user', function () {
    return view('dashboard');
})->name('dashboard');

