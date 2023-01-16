<?php

namespace App\Traits;

use App\Exceptions\MissingDotEnvFileException;
use App\Exceptions\MissingOpenAIKeyException;
use App\Services\AI\OpenAIService;
use App\Utils\CheckDotEnv;

trait OpenAICommand
{

    /**
     * Check all prerequisites for OpenAI and inform the user if something is missing
     *
     * @param OpenAIService $openAIService
     * @return bool
     * @throws \Exception unable to create .env file
     */
     private function checkOpenAI(OpenAIService $openAIService): bool
    {
        try {
            $openAIService->buildClient();
            return true;
        }catch(MissingDotEnvFileException $e){
            $this->error('.env file is not present');
            $this->info('I\'m creating a .env file for you');
            CheckDotEnv::create();
            $this->info('Please edit the `.env` file and add your OpenAI API key');
            $this->info('You can get your API key from https://beta.openai.com/account/api-keys');
        }catch(MissingOpenAIKeyException $e){
            $this->error('OpenAI API key is not present in .env file');
            $this->info('Create a .env file in the same directory of a80 and add the following line:');
            $this->info('OPENAI_API_KEY=your_api_key');
            $this->info('You can get your API key from https://beta.openai.com/account/api-keys');
        }
        return false;
    }
}
