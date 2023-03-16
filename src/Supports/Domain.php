<?php

namespace LaravelReady\UrlShortener\Supports;

use LaravelReady\UrlShortener\Models\ShortUrlFavicon;
use LaravelReady\UrlShortener\Supports\Favicon;

class Domain
{
    /**
     * Get parsed domain info
     *
     * @param string $url : sended url
     * @return array|bool
     */
    public static function getDomainInfo($url)
    {
        if ($url) {
            $data = parse_url($url);

            $favicon = ShortUrlFavicon::where('domain', $data['host'])->first();

            if (!$favicon) {
                $favicon = ShortUrlFavicon::create([
                    'domain' => $data['host'],
                    'favicon' => (new Favicon())->getFavicon($data['host']),
                ]);
            }

            $data['favicon_id'] = $favicon->id ?? null;

            return $data;
        }

        return false;
    }
}
