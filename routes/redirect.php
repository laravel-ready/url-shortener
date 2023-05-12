<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Config;

Route::name('url-shortener.redirect.')->prefix(Config::get('url-shortener.redirect_route_prefix', 'r'))->group(function () {
    Route::get('{short_code}', Config::get('url-shortener.redirect_controller', \LaravelReady\UrlShortener\Http\Controllers\RedirectController::class))->name('index');
});
