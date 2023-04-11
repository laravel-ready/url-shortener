<?php

namespace LaravelReady\UrlShortener\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use LaravelReady\UrlShortener\Supports\Emoji;
use LaravelReady\UrlShortener\Supports\Eloquent;

class EmojiController extends Controller
{
    public function __invoke()
    {
        $emojisQuery = Emoji::getBaseEmojiQuery();
        $emojisQueryForCacheKey = clone $emojisQuery;

        $cacheKey = Config::get('url-shortener.table_name', 'short_url') . '_emojis.' . md5($emojisQueryForCacheKey->toSql());

        if (Cache::has($cacheKey)) {
            return response()->json(
                Cache::get($cacheKey, [])
            );
        }

        Eloquent::initNewDbConnection();

        $emojis = $emojisQuery->get();

        Eloquent::restorePreviousDbConnection();

        Cache::put($cacheKey, $emojis, now()->addYear());

        return response()->json($emojis);
    }
}
