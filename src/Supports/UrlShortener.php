<?php

namespace LaravelReady\UrlShortener\Supports;

use Illuminate\Support\Facades\Config;
use LaravelReady\UrlShortener\Models\ShortUrl;
use LaravelReady\UrlShortener\Models\ShortUrlGroup;

class UrlShortener
{
    public function shortUrl(string|array $urls, array $data): mixed
    {
        if (is_array($urls)) {
            $shortUrls = [];

            $group = ShortUrlGroup::create();

            foreach ($urls as $url) {
                $shortUrls[] = $this->shortSingleUrl($url, $data, $group->id);
            }

            return $shortUrls;
        } else {
            return $this->shortSingleUrl($urls, $data);
        }
    }

    public function shortSingleUrl(string $url, array $data, int $groupId = null): ShortUrl
    {
        $domain = Domain::getDomainInfo($url);

        return ShortUrl::firstOrCreate([
            'url' => $url,
            'title' => $data['title'],
            'description' => $data['description'],
            'short_code' => $this->getRandomString(),
            'type' => $data['type'],
            'delay' => $data['delay'],
            'expire_date' => $data['expire_date'],
            'password' => $data['password'],
            'favicon_id' => $domain['favicon_id'],
            'group_id' => $groupId
        ]);
    }

    public function getRandomString(int $retry = 10): string
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
                return $this->getRandomString($retry - 1);
            } else {
                return false;
            }
        }

        return $randomString;
    }
}
