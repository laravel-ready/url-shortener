<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Config;
use LaravelReady\UrlShortener\Http\Controllers\UrlShortenerController;
use LaravelReady\UrlShortener\Http\Controllers\EmojiController;

if (Config::get('url-shortener.api.enable', true)) {
    Route::prefix(Config::get('url-shortener.api.route', 'api/url-shortener'))->name('url-shortener.api.')->group(function () {
        Route::get('short-urls', [UrlShortenerController::class, 'index'])->name('short-urls');
        Route::get('emojis', EmojiController::class)->name('emojis');
    });
}
