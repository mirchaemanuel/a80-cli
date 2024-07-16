<?php

namespace App\Enums\AI;

use App\Services\AI\OpenAIService;

enum OpenAIModel: string
{
    case davinci = 'text-davinci-003';
    case curie = 'text-curie-001';
    case babbage = 'text-babbage-001';
    case ada = 'text-ada-001';

    case gpt4o = 'gpt-4o';

    public static function getModel(string $name): ?OpenAIModel
    {
        return match ( $name ) {
            self::davinci->name => self::davinci,
            self::curie->name   => self::curie,
            self::babbage->name => self::babbage,
            self::ada->name     => self::ada,
            default             => null
        };
    }
}
