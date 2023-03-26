<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Config;
use LaravelReady\UrlShortener\Http\Controllers\EmojiController;

if (Config::get('url-shortener.api.enable', true)) {
    Route::name('url-shortener.api.')->prefix(Config::get('url-shortener.api.route', 'api/url-shortener'))->group(function () {
        Route::get('', EmojiController::class)->name('index');
    });
}
