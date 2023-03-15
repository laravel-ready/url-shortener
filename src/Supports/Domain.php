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
            $pattern = '/^(https|http|ftp):\/\/(.*?)\/?/';
            preg_match($pattern, "{$url}/", $matches);

            $parts = explode('.', $matches[2]);
            $tld = array_pop($parts);
            $host = array_pop($parts);

            if (strlen($tld) == 2 && strlen($host) <= 3) {
                $tld = "{$host}.{$tld}";
                $host = array_pop($parts);
            }

            $info = [
                'protocol' => $matches[1],
                'subdomain' => implode('.', $parts),
                'domain' => "{$host}.{$tld}",
                'host' => $host,
                'tld' => $tld,
            ];

            $parse = parse_url($url);

            $data = array_merge($info, $parse);

            $favicon = ShortUrlFavicon::where('domain', $data['domain'])->first();

            if (!$favicon) {
                $favicon = ShortUrlFavicon::create([
                    'domain' => $data['domain'],
                    'favicon' => (new Favicon())->getFavicon($data['domain']),
                ]);
            }

            $data['favicon_id'] = $favicon->id ?? null;

            return $data;
        }

        return false;
    }
}
