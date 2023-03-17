<?php

namespace LaravelReady\UrlShortener\Supports;

use Illuminate\Support\Facades\Config;
use Illuminate\Database\Eloquent\Builder;
use LaravelReady\UrlShortener\Models\Emoji\UnicodeEmoji;

class Emoji
{
    public static function getBaseEmojiQuery(): Builder
    {
        return UnicodeEmoji::with([
            'version',
            'emojiVersion',
        ])->whereHasIn('version', function($query) {
            $query->whereRaw('CAST(tag AS INTEGER) BETWEEN ? AND ?', [
                Config::get('url-shortener.emoji.min_unicode_version', 1), 
                Config::get('url-shortener.emoji.max_unicode_version', 15)
            ]);
        })->whereHasIn('emojiVersion', function($query) {
            $query->whereRaw('CAST(tag AS INTEGER) BETWEEN ? AND ?', [
                Config::get('url-shortener.emoji.min_emoji_version', 1), 
                Config::get('url-shortener.emoji.max_emoji_version', 15)
            ]);
        })
        ->where([
            ['isLayered', '=', 0],
            ['hasZeroWidthSpace', '=', 0],
            ['isSupportingByChromium', '=', 1],
        ]);
    }
}