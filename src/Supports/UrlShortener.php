<?php

namespace LaravelReady\UrlShortener\Supports;

use LaravelReady\UrlShortener\Exceptions\UrlShortenerException;
use Illuminate\Support\Facades\Config;
use LaravelReady\UrlShortener\Models\ShortUrl;
use LaravelReady\UrlShortener\Supports\Eloquent;
use LaravelReady\UrlShortener\Enums\ShortingType;
use LaravelReady\UrlShortener\Models\ShortUrlGroup;

class UrlShortener
{
    public static function shortUrl(string|array $urls, array $data, ShortingType $type = ShortingType::Random): mixed
    {
        if (is_array($urls)) {
            $shortUrls = [];

            $group = ShortUrlGroup::create();

            foreach ($urls as $url) {
                $shortUrls[] = self::shortSingleUrl($url, $data, $group->id, $type);
            }

            return $shortUrls;
        } else if (is_string($urls)) {
            return self::shortSingleUrl($urls, $data, null, $type);
        }

        return null;
    }

    public static function shortSingleUrl(string $url, array $data, int $groupId = null, ShortingType $type): ShortUrl
    {
        $domain = Domain::getDomainInfo($url);

        $shortUrl = ShortUrl::where([
            ['url', '=', $url],
            ['group_id', '=', $groupId],
            ['user_id', '=', auth()->id()],
            ['type', '=', $type],
        ])->first();

        if ($shortUrl) {
            return $shortUrl;
        }

        $shortCode = null;

        if ($type === ShortingType::Custom || $type === ShortingType::EmojiCustom) {
            $shortCode = $data['short_code'];

            if (!isset($data['short_code']) || empty($data['short_code'])) {
                throw new UrlShortenerException('Short code is required');
            }
        } else if ($type === ShortingType::Random || $type === ShortingType::Emoji) {
            if ($type === ShortingType::Emoji && Config::get('url-shortener.emoji.allow', true)) {
                $shortCode = self::getRandomEmojiString();
            } else {
                $shortCode = self::getRandomString();
            }
        }

        return ShortUrl::create([
            'url' => $url,
            'title' => $data['title'] ?? null,
            'description' => $data['description'] ?? null,
            'short_code' => $shortCode,
            'type' => $type,
            'delay' => $data['delay'] ?? 0,
            'expire_date' => $data['expire_date'] ?? null,
            'password' => $data['password'] ?? null,
            'favicon_id' => $domain['favicon_id'] ?? null,
            'group_id' => $groupId
        ]);
    }

    private static function getRandomString(int $retry = 10): string
    {
        $characters = Config::get('url-shortener.characters');
        $minLength = Config::get('url-shortener.min_length');
        $maxLength = Config::get('url-shortener.max_length');

        $charactersLength = strlen($characters);

        if ($charactersLength < 1) {
            throw new UrlShortenerException('Characters length must be greater than 0');
        } else if ($minLength < 1) {
            throw new UrlShortenerException('Min length must be greater than 0');
        } else if ($maxLength < 1) {
            throw new UrlShortenerException('Max length must be greater than 0');
        } else if ($minLength > $maxLength) {
            throw new UrlShortenerException('Min length must be less than max length');
        } else if ($charactersLength < $maxLength) {
            throw new UrlShortenerException('Characters length must be greater than max length');
        }

        $randomString = '';

        $shortLength = rand($minLength, $maxLength);

        for ($i = 0; $i < $shortLength; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        if (ShortUrl::where('short_code', $randomString)->exists()) {
            if ($retry > 0) {
                return self::getRandomString($retry - 1);
            } else {
                return false;
            }
        }

        return $randomString;
    }

    private static function getRandomEmojiString(): string
    {
        Eloquent::initNewDbConnection();

        $emojis = Emoji::getBaseEmojiQuery(['emoji'])->get();

        Eloquent::restorePreviousDbConnection();

        if (!$emojis) {
            throw new UrlShortenerException('No emoji found');
        }

        $emojiList = $emojis->pluck('emoji');
        $emojiListCount = $emojiList->count();
        $minLength = Config::get('url-shortener.min_length');
        $maxLength = Config::get('url-shortener.max_length');

        $randomString = '';

        $shortLength = rand($minLength, $maxLength);

        for ($i = 0; $i < $shortLength; $i++) {
            $randomString .= $emojiList[rand(0, $emojiListCount - 1)];
        }

        return $randomString;
    }
}
