<?php

namespace App\Utils;

use Illuminate\Support\Facades\Storage;

class CheckDotEnv
{

    /**
     * Check if the .env file is present and readable
     *
     * @return bool true if .env file is missing
     */
    public static function exists(): bool
    {
        return Storage::exists('.env');
    }

    /**
     * Create a .env file
     *
     * @return void
     * @throws \Exception if .env is not writable
     */
    public static function create(): void
    {
        //check if .env file exists, otherwise create it
        if (self::exists()) {
            return;
        }

        try {
            //skelton .env file
            $envSkel = <<<TXT
# OpenAI
OPENAI_API_KEY=your_api_key
TXT;
            Storage::put('.env', $envSkel);
        } catch (\Exception $ex) {
            echo $ex->getMessage() . "\n";
            throw new \Exception('Unable to create .env file');
        }
    }
}
