<?php

namespace LaravelReady\UrlShortener;

use Illuminate\Support\Facades\Config;

class UrlShortener {
    public function getRandomString(): string {
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

        return $randomString;
    }
}