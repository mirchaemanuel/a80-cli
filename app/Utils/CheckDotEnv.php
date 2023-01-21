<?php

namespace App\Utils;

use Exception;
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
     * @throws Exception if .env is not writable
     */
    public static function create(): void
    {
        //check if .env file exists, otherwise create it
        if (self::exists()) {
            return;
        }

        try {
            //skeleton .env file
            Storage::disk('local')->put('.env', Storage::disk('internal')->get('resources/skel/dot_env'));
        } catch (Exception $ex) {
            echo $ex->getMessage() . "\n";
            throw new Exception('Unable to create .env file');
        }
    }
}
