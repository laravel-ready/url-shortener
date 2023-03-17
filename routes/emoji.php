<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Config;
use LaravelReady\UrlShortener\Http\Controllers\EmojiController;

Route::name('url-shortener.emoji.')->prefix(Config::get('url-shortener.emoji.base_route', 'emojis'))->group(function () {
    Route::get('', EmojiController::class)->name('index');
});
