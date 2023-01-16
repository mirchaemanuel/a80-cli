<?php

namespace App\Services\AI;

use App\Enums\AI\OpenAIModel;
use App\Exceptions\MissingDotEnvFileException;
use App\Exceptions\MissingOpenAIKeyException;
use App\Utils\CheckDotEnv;
use OpenAI;
use OpenAI\Client as OpenAIClient;
use OpenAI\Responses\Completions\CreateResponse;

/**
 * OpenAI Service
 *
 *
 */
class OpenAIService
{
    private  OpenAIClient $client;

    public function __construct()
    {

    }

    /**
     * @return OpenAIService ready to be used
     *
     * @throws MissingDotEnvFileException
     * @throws MissingOpenAIKeyException
     */
    public function buildClient() : OpenAIService
    {
        //check if .env file exists, otherwise create it
        if (CheckDotEnv::exists() === false) {
            throw new MissingDotEnvFileException();
        }

        //check .env file
        $openaiKey = config('ai.openai.api_key');
        if (empty($openaiKey)) {
            throw new MissingOpenAIKeyException();
        }

        $this->client = OpenAI::client($openaiKey);

        return $this;
    }

    /**
     * Prompt OpenAI
     *
     * @param string $prompt
     * @param int $maxTokens
     * @param OpenAIModel $model
     * @param float $temperature
     *
     * @return CreateResponse
     * @throws MissingDotEnvFileException
     * @throws MissingOpenAIKeyException
     */
    public function prompt(string      $prompt,
                           int         $maxTokens = 2000,
                           OpenAIModel $model = OpenAIModel::davinci,
                           float       $temperature = 0.9,

    )
    {
        if($this->client === null) {
            $this->buildClient();
        }

        return $this->client->completions()->create([
            'model'       => $model->value,
            'prompt'      => $prompt,
            'max_tokens'  => $maxTokens,
            'temperature' => $temperature,
        ]);
    }

}
