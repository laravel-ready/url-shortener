<?php

namespace LaravelReady\UrlShortener\Supports;

use Illuminate\Support\Facades\Config;
use Illuminate\Database\Eloquent\Builder;
use LaravelReady\UrlShortener\Models\Emoji\UnicodeEmoji;

class Emoji
{
    /**
     * Get base emoji query
     * 
     * This query is used to get most basic emojis
     * 
     * @return Builder
     */
    public static function getBaseEmojiQuery(array $select = []): Builder
    {
        $query = UnicodeEmoji::with([
            'version',
            'emojiVersion',
        ])->whereHasIn('version', function ($query) {
            $query->whereRaw('CAST(tag AS INTEGER) BETWEEN ? AND ?', [
                Config::get('url-shortener.emoji.min_unicode_version', 1),
                Config::get('url-shortener.emoji.max_unicode_version', 15)
            ]);
        })->whereHasIn('emojiVersion', function ($query) {
            $query->whereRaw('CAST(tag AS INTEGER) BETWEEN ? AND ?', [
                Config::get('url-shortener.emoji.min_emoji_version', 1),
                Config::get('url-shortener.emoji.max_emoji_version', 15)
            ]);
        })->where([
            ['isLayered', '=', 0],
            ['hasZeroWidthSpace', '=', 0],
            ['isSupportingByChromium', '=', 1],
        ]);

        if (count($select)) {
            $query->select($select);
        }

        return $query;
    }

    /**
     * Returns emojis string length
     *
     * @param $emojis: emoji characters
     * @return int
     */
    public static function emojiLen($emojis): int
    {
        return $emojis ? count(preg_split('~\X{1}\K~u', $emojis)) - 1 : 0;
    }
}
