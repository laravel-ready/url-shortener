<?php

namespace LaravelReady\UrlShortener\Enums;

enum ShortingType: string
{
    case Random = 'random';
    case Custom = 'custom';
    case Emoji = 'emoji';
    case EmojiCustom = 'emoji_custom';
}
