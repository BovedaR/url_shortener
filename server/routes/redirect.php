<?php

use Illuminate\Support\Facades\Route;

Route::prefix('')->group(function () {
    Route::get('/{collection}/{shortUrl}', 'App\Http\Controllers\RedirectController@redirectWithCollection')->where(['shortUrl' => '[a-zA-Z0-9]{6}']);
    Route::get('/{shortUrl}', 'App\Http\Controllers\RedirectController@redirectWithoutCollection')->where(['shortUrl' => '[a-zA-Z0-9]{6}']);
});