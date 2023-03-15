<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Config;
use LaravelReady\UrlShortener\Http\Controllers\RedirectController;

Route::name('url-shortener.redirect.')->prefix(Config::get('url-shortener.redirect_route_prefix', 'r'))->group(function () {
    Route::get('{short_code}', RedirectController::class)->name('index');
});
