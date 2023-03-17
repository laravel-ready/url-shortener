<?php

namespace LaravelReady\UrlShortener\Http\Controllers;

use Illuminate\Routing\Controller;
use LaravelReady\UrlShortener\Models\ShortUrl;

class RedirectController extends Controller {
    public function __invoke(string $shortCode) {
        $shortUrl = ShortUrl::where('short_code', $shortCode)->firstOrFail();

        return redirect($shortUrl->url);
    }
}