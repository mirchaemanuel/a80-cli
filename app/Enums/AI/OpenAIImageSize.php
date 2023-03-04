<?php

namespace App\Enums\AI;

enum OpenAIImageSize: string
{
    case small = '256x256';
    case medium = '512x512';
    case large = '1024x1024';

    public static function getSize(string $size): ?OpenAIImageSize
    {
        return match ( $size ) {
            self::small->name => self::small,
            self::medium->name => self::medium,
            self::large->name => self::large,
            default => null
        };
    }
}
