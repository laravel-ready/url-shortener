<?php

namespace LaravelReady\UrlShortener\Models\Emoji;

use Illuminate\Database\Eloquent\Model;

class UnicodeEmoji extends Model
{    protected $table = 'Unicode_Emoji';

    public function version()
    {
        return $this->belongsTo(UnicodeVersion::class, 'unicode_VersionId');
    }

    public function emojiVersion()
    {
        return $this->belongsTo(UnicodeEmojiVersion::class, 'unicode_Emoji_VersionId');
    }
}
