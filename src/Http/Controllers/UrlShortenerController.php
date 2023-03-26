<?php

namespace LaravelReady\UrlShortener\Http\Controllers;

use Illuminate\Routing\Controller;
use LaravelReady\UrlShortener\Models\ShortUrl;

class UrlShortenerController extends Controller
{
    public function index()
    {
        $shortUrls = ShortUrl::where([
            ['user_id', auth()->id()],
        ])->paginate(
            request()->get('per_page', 10)
        );

        return $shortUrls;
    }
}
