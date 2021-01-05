<?php

use Illuminate\Support\Facades\Route;

Route::get('/','App\Http\Controllers\IndexController@index');

Route::get('/parapara/{name}', 'App\Http\Controllers\IndexController@paraparaView');

Route::middleware(['auth:sanctum', 'verified'])->get('save',function(){
    return view('save');
});

Route::get('/v1/image/thumbnailPub','App\Http\Controllers\ImageController@thumbnailPublic');

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/manga', 'App\Http\Controllers\MangaController@index');
    Route::get('/create', 'App\Http\Controllers\CreateController@index');
    Route::get('/v1/image/myMangaThumbnaiAll', 'App\Http\Controllers\ImageController@myMangaThumbnaiAll');
    Route::Resource('/v1/image', 'App\Http\Controllers\ImageController');
    Route::get('/edit/{id}','App\Http\Controllers\EditController@edit');
    Route::get('info','App\Http\Controllers\IndexController@info');
});

Route::middleware(['auth:sanctum', 'verified'])->get('/user', function () {
    return view('dashboard');
})->name('dashboard');

