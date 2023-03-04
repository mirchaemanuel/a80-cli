<?php

namespace App\Enums\AI;

enum OpenAIImageSize: string
{
    case SMALL = '256x256';
    case MEDIUM = '512x512';
    case LARGE = '1024x1024';

    public static function getSize(string $size): ?OpenAIImageSize
    {
        return match ( strtoupper($size) ) {
            self::SMALL->name  => self::SMALL,
            self::MEDIUM->name => self::MEDIUM,
            self::LARGE->name  => self::LARGE,
            default            => null
        };
    }
}
