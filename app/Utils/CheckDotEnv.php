<?php

namespace App\Utils;

class CheckDotEnv
{

    /**
     * Check if the .env file is present and readable
     *
     * @return bool true if .env file is missing
     */
    public static function exists(): bool
    {
        return file_exists(base_path('.env'));
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
            //create .env file
            $env     = fopen(base_path('.env'), 'wb');

            //skelton .env file
            $envSkel = <<<TXT
# OpenAI
OPENAI_API_KEY=your_api_key
TXT;
            fwrite($env, $envSkel);
            fclose($env);
        } catch (\Exception) {
            throw new \Exception('Unable to create .env file');
        }
    }
}
