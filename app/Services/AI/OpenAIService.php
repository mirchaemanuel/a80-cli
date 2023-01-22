<?php

namespace App\Services\AI;


use App\Enums\AI\OpenAIModel;
use App\Exceptions\MissingDotEnvFileException;
use App\Exceptions\MissingOpenAIKeyException;
use OpenAI\Responses\Completions\CreateResponse;

/**
 * OpenAI Service
 *
 *
 */
interface OpenAIService
{
    /**
     * @return OpenAIServiceV1 ready to be used
     *
     * @throws MissingDotEnvFileException
     * @throws MissingOpenAIKeyException
     */
    public function buildClient(): OpenAIService;

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
    public function prompt(string $prompt, int $maxTokens = 2000, OpenAIModel $model = OpenAIModel::davinci, float $temperature = 0.9,);
}
