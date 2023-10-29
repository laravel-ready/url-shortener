<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Config;
use LaravelReady\UrlShortener\Http\Controllers\UrlShortenerController;
use LaravelReady\UrlShortener\Http\Controllers;

if (Config::get('url-shortener.api.enable', true)) {
    Route::prefix(Config::get('url-shortener.api.route', 'api/v1/url-shortener'))->name('url-shortener.api.')->group(function () {
        Route::get('short-urls', [UrlShortenerController::class, 'index'])->name('short-urls');
        // Route::get('emojis', EmojiController::class)->name('emojis');

        Route::prefix('emojis')->group(function () {
            Route::resource('', Controllers\EmojiController::class)->only(['index', 'show'])->parameters(['' => 'emoji']);
            Route::get('all', [Controllers\EmojiController::class, 'all'])->name('emojis.all');
            Route::patch('{emoji}/status', [Controllers\EmojiController::class, 'updateStatus'])->name('emojis.update-status');
        });
    });
}
