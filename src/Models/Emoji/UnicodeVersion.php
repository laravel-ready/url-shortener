<?php

namespace LaravelReady\UrlShortener\Models\Emoji;

use Illuminate\Database\Eloquent\Model;

class UnicodeVersion extends Model
{    protected $table = 'Unicode_Version';

    protected $casts = [
        'id' => 'integer',
        'tag' => 'float',
    ];
}
