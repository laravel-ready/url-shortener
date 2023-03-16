<?php

namespace LaravelReady\UrlShortener\Supports;

use Illuminate\Support\Facades\Http;

class Favicon
{
    /**
     * Get favicon from target url
     *
     * @param string $url : target URL
     * @return null|string
     */
    public function getFavicon(string $domain): string|null
    {
        try {
            $url = "http://www.google.com/s2/favicons?domain={$domain}";

            $response = Http::get($url);

            if ($response->getStatusCode() == 200) {
                return base64_encode($response->getBody()->getContents());
            }
        } catch (\Throwable $th) {
            //throw $th;
        }

        return null;
    }
}
