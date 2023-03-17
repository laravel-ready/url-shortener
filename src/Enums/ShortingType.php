<?php

namespace LaravelReady\UrlShortener\Enums;

enum ShortingType: string
{
    case Random = 'random';
    case Custom = 'custom';
    case EmojiRandom = 'emoji_random';
    case EmojiCustom = 'emoji_custom';
}
