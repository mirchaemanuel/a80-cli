<?php

namespace App\Services\AI;


use App\Enums\AI\OpenAIImageSize;
use App\Enums\AI\OpenAIModel;
use App\Exceptions\MissingDotEnvFileException;
use App\Exceptions\MissingOpenAIKeyException;
use Hamcrest\Text\StringContains;
use OpenAI\Responses\Completions\CreateResponse;

/**
 * OpenAI Service
 *
 *
 */
interface OpenAIService
{
    /**
     * @return OpenAIService ready to be used
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

    /**
     * Create an image from a prompt
     *
     * @param string $prompt prompt to use for the image
     * @param OpenAIImageSize $size size of the image to create
     * @param int $count number of images to create
     * @return array list of image data of the generated images
     */
    public function imageCreate(string $prompt, OpenAIImageSize $size, int $count = 1): array;

    /**
     * Transcribe audio to text
     *
     * @param string $fileName name of the audio file (mp3, mp4, mpeg, mpga, m4a, wav, or webm)
     * @param string $language language of the audio file (ISO 639-1)
     * @return string text from the audio file
     */
    public function trascribe(string $fileName, string $language = null): string;
}
