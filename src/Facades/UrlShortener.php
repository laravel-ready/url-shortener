<?php
namespace LaravelReady\UrlShortener\Facades;

use Illuminate\Support\Facades\Facade;

class UrlShortener extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'url-shortener';
    }
}
