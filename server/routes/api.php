<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::prefix('auth')->group(function () {
    Route::get('/', 'App\Http\Controllers\AuthController@user');
    Route::post('/login', 'App\Http\Controllers\AuthController@login');
    Route::post('/logout', 'App\Http\Controllers\AuthController@logout');
    Route::post('/refresh', 'App\Http\Controllers\AuthController@refresh');
    Route::post('/register', 'App\Http\Controllers\AuthController@register');
});

Route::prefix('urls')->group(function () {
    Route::get('/', 'App\Http\Controllers\UrlController@index');
    Route::post('/', 'App\Http\Controllers\UrlController@store');
    Route::get('/{url}', 'App\Http\Controllers\UrlController@show');
    Route::put('/{url}', 'App\Http\Controllers\UrlController@update');
    Route::delete('/{url}', 'App\Http\Controllers\UrlController@destroy');
});

Route::prefix('urlcollections')->group(function () {
    Route::get('/', 'App\Http\Controllers\UrlCollectionController@index');
    Route::post('/', 'App\Http\Controllers\UrlCollectionController@store');
    Route::get('/{urlCollection}', 'App\Http\Controllers\UrlCollectionController@show');
    Route::put('/{urlCollection}', 'App\Http\Controllers\UrlCollectionController@update');
    Route::delete('/{urlCollection}', 'App\Http\Controllers\UrlCollectionController@destroy');
});