<?php

namespace LaravelReady\UrlShortener\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use LaravelReady\UrlShortener\Models\ShortUrl;

class RedirectController extends Controller
{
    public function __invoke(string $shortCode)
    {
        $shortUrl = ShortUrl::where('hash', md5($shortCode))->firstOrFail();

        return redirect($shortUrl->url);
    }
}
