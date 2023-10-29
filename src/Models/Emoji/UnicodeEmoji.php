<?php

namespace LaravelReady\UrlShortener\Models\Emoji;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UnicodeEmoji extends Model
{
    public $fillable = [
        'emoji',
        'name',
        'description',
        'codePoint',
        'shortCode',
        'isLayered',
        'hasZeroWidthSpace',
        'testedChromiumVersion',
        'slug',
        'keywords',
        'groupName',
        'isActive',
    ];

    public $timestamps = false;

    protected $table = 'Unicode_Emoji';

    protected $casts = [
        'isActive' => 'boolean',
    ];

    public function version(): BelongsTo
    {
        return $this->belongsTo(UnicodeVersion::class, 'unicode_VersionId');
    }

    public function emojiVersion(): BelongsTo
    {
        return $this->belongsTo(UnicodeEmojiVersion::class, 'unicode_Emoji_VersionId');
    }
}
