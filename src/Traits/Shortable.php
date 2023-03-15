<?php

namespace LaravelReady\UrlShortener\Traits;

use Illuminate\Database\Eloquent\Relations\MorphOne;

use LaravelReady\UrlShortener\Models\ShortUrl;

trait Shortable
{
    /**
     * Shortable model polymorphic relationship
     */
    public function shortable(): MorphOne
    {
        return $this->morphOne(ShortUrl::class, 'shortable');
    }
}
