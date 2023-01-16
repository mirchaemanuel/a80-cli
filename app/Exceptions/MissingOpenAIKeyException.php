<?php

namespace App\Exceptions;

class MissingOpenAIKeyException extends \Exception
{
    public function __construct()
    {
        parent::__construct('Missing OpenAI API key: Please edit the `.env` file and add your OpenAI API key. You can get your API key from https://beta.openai.com/account/api-keys');
    }
}
