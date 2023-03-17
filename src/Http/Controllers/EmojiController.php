<?php

namespace LaravelReady\UrlShortener\Http\Controllers;

use Illuminate\Routing\Controller;
use LaravelReady\UrlShortener\Supports\Emoji;
use LaravelReady\UrlShortener\Supports\Eloquent;

class EmojiController extends Controller
{
    public function __invoke()
    {
        Eloquent::initNewDbConnection();

        $emojis = Emoji::getBaseEmojiQuery()->get();

        Eloquent::restorePreviousDbConnection();

        return response()->json($emojis);
    }
}
