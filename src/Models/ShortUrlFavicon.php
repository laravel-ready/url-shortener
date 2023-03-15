<?php

namespace LaravelReady\UrlShortener\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Config;

class ShortUrlFavicon extends Model
{
    public function __construct(array $attributes = [])
    {
        $this->table = Config::get('url-shortener.table_name', 'short_url') . '_favicons';

        parent::__construct($attributes);
    }

    protected $table = 'short_url_favicons';

    protected $fillable = [
        'domain',
        'favicon',
    ];

    public function shortUrls(): HasMany
    {
        return $this->hasMany(ShortUrl::class, 'favicon_id');
    }
}
