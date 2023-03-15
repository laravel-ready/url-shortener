<?php

namespace LaravelReady\UrlShortener\Supports;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;

class Favicon
{
    /**
     * Get favicon from target url
     *
     * @param string $url : target URL
     * @return null|string
     */
    public function getFavicon(string $domain): string
    {
        $client = new Client(['base_uri' => 'http://www.google.com/s2/']);
        $response = $client->request('GET', "favicons?domain={$domain}");

        if ($response->getStatusCode() == 200) {
            return base64_encode($response->getBody()->getContents());
        }

        return null;
    }
}
