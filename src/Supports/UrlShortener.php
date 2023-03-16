<?php

namespace LaravelReady\UrlShortener\Supports;

use Illuminate\Support\Facades\Config;
use LaravelReady\UrlShortener\Enums\ShortingType;
use LaravelReady\UrlShortener\Models\ShortUrl;
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

        return ShortUrl::create([
            'url' => $url,
            'title' => $data['title'],
            'description' => $data['description'],
            'short_code' => self::getRandomString(),
            'type' => $type,
            'delay' => $data['delay'] ?? 0,
            'expire_date' => $data['expire_date'] ?? null,
            'password' => $data['password'] ?? null,
            'favicon_id' => $domain['favicon_id'] ?? null,
            'group_id' => $groupId
        ]);
    }

    public static function getRandomString(int $retry = 10): string
    {
        $characters = Config::get('url-shortener.characters');
        $minLength = Config::get('url-shortener.min_length');
        $maxLength = Config::get('url-shortener.max_length');

        $charactersLength = strlen($characters);

        if ($charactersLength < 1) {
            throw new \Exception('Characters length must be greater than 0');
        } else if ($minLength < 1) {
            throw new \Exception('Min length must be greater than 0');
        } else if ($maxLength < 1) {
            throw new \Exception('Max length must be greater than 0');
        } else if ($minLength > $maxLength) {
            throw new \Exception('Min length must be less than max length');
        } else if ($charactersLength < $maxLength) {
            throw new \Exception('Characters length must be greater than max length');
        }

        $randomString = '';

        for ($i = 0; $i < $maxLength; $i++) {
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
}
