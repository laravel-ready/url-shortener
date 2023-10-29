<?php

namespace LaravelReady\UrlShortener\Traits;

use Illuminate\Support\Facades\Cache;

trait EmojiCacheTrait
{
    protected string $cacheKey = 'url_shortener:emojis';

    public function getCachedEmojis(string $key)
    {
        return Cache::get("{$this->cacheKey}:{$key}", []);
    }

    public function setCacheForEmojis(string $key, mixed $emojis)
    {
        $cacheKeysKey = "{$this->cacheKey}:cache_keys";

        $shortenerEmojisKeys = Cache::get($cacheKeysKey, []);

        if (!in_array($key, $shortenerEmojisKeys)) {
            $shortenerEmojisKeys[] = $key;

            Cache::rememberForever($cacheKeysKey, function () use ($shortenerEmojisKeys) {
                return $shortenerEmojisKeys;
            });
        }

        Cache::put("{$this->cacheKey}:{$key}", $emojis, now()->addYear());
    }

    public function clearEmojisCache()
    {
        $cacheKeysKey = "{$this->cacheKey}:cache_keys";

        $shortenerEmojisKeys = Cache::get($cacheKeysKey, []);

        foreach ($shortenerEmojisKeys as $key) {
            Cache::forget("{$this->cacheKey}:{$key}");
        }

        Cache::forget($cacheKeysKey);
    }
}
