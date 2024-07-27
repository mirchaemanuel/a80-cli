<?php

namespace App\Services\AI\Concretes;

use App\Enums\AI\OpenAIImageSize;
use App\Enums\AI\OpenAIModel;
use App\Exceptions\MissingDotEnvFileException;
use App\Exceptions\MissingOpenAIKeyException;
use App\Exceptions\UnsupportedAudioFormatException;
use App\Services\AI\OpenAIService;
use App\Utils\CheckDotEnv;
use OpenAI;
use OpenAI\Client as OpenAIClient;
use OpenAI\Responses\Completions\CreateResponse;
use Phar;

/**
 * OpenAI Service
 *
 *
 */
class OpenAIServiceV1 implements OpenAIService
{
    private OpenAIClient $client;

    public function __construct()
    {

    }

    /**
     * @return OpenAIServiceV1 ready to be used
     *
     * @throws MissingDotEnvFileException
     * @throws MissingOpenAIKeyException
     */
    public function buildClient(): OpenAIServiceV1
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
                           OpenAIModel $model = OpenAIModel::gpt4o,
                           float       $temperature = 0.9,

    )
    {
        if ($this->client === null) {
            $this->buildClient();
        }

        return $this->client->chat()->create([
            'model'       => $model->value,
            'max_tokens'  => $maxTokens,
            'temperature' => $temperature,
            "messages"    => [
                [
                    "role"    => "system",
                    "content" => "You are a helpful assistant."
                ],
                [
                    "role"    => "user",
                    "content" => $prompt
                ]
            ]
        ]);
    }

    /**
     * Create an image from a prompt
     *
     * @param string $prompt prompt to use for the image
     * @param OpenAIImageSize $size size of the image to create
     * @param int $count number of images to create
     * @return array list of image data of the generated images
     * @throws MissingDotEnvFileException
     * @throws MissingOpenAIKeyException
     */
    public function imageCreate(string $prompt, OpenAIImageSize $size, int $count = 1): array
    {
        if ($this->client === null) {
            $this->buildClient();
        }

        if ($count > 10) {
            $count = 10;
        } elseif ($count < 1) {
            $count = 1;
        }

        $response = $this->client->images()->create([
            'prompt'          => $prompt,
            'n'               => $count,
            'size'            => $size,
            'response_format' => 'b64_json'
        ]);

        $images = [];
        foreach ($response->data as $data) {
            $images[] = base64_decode($data->b64_json);
        }

        return $images;
    }

    public function text2speech(string $text, string $voice = 'onyx', float $speed = 0.95): string
    {
        if ($this->client === null) {
            $this->buildClient();
        }

        $response = $this->client->audio()->speech([
            'model' => 'tts-1',
            'input' => $text,
            'voice' => $voice,
            'speed' => $speed
        ]);

        return $response;
    }

    /**
     * Transcribe audio to text
     *
     * @param string $fileName name of the audio file (mp3, mp4, mpeg, mpga, m4a, wav, or webm)
     * @param string $language language of the audio file (ISO 639-1)
     * @return string text from the audio file
     */
    public function trascribe(string $fileName, string $language = null): string
    {
        if ($this->client === null) {
            $this->buildClient();
        }

        //check if audio file is supported by file extension
        $supportedAudioFormats = ['mp3', 'mp4', 'mpeg', 'mpga', 'm4a', 'wav', 'webm'];
        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
        if (in_array($fileExtension, $supportedAudioFormats, true) === false) {
            throw new UnsupportedAudioFormatException();
        }

        //if fiel not exists
        if (!file_exists($fileName)) {
            throw new \Exception('File not found');
        }
        //transcribe

        if (Phar::running()) {
            /**
             * Instructs phar to intercept fopen, file_get_contents, opendir, and all of the stat-related functions
             */
            Phar::interceptFileFuncs();
        }
        $params = [
            'model' => 'whisper-1',
            'file'  => fopen($fileName, 'r'),
        ];
        if ($language !== '') {
            $params['language'] = $language;
        }

        $response = $this->client->audio()->transcribe([
            'model'           => 'whisper-1',
            'file'            => fopen('/Users/ryuujin/workspace/dev2geek/a80-cli/foo.mp4', 'r'),
            'response_format' => 'verbose_json',


        ]);

        dd($response);

        return $response->toArray()['text'];
    }


}

