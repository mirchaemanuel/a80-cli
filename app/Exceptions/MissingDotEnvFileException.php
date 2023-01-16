<?php

namespace App\Exceptions;

class MissingDotEnvFileException extends \Exception
{
    public function __construct()
    {
        parent::__construct('Missing .env file');
    }
}
