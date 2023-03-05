<?php

namespace App\Commands\AI;

use App\Enums\AI\OpenAIImageSize;
use App\Services\AI\OpenAIService;
use App\Traits\OpenAICommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use LaravelZero\Framework\Commands\Command;

class AudioTranscribe extends Command
{
    use OpenAICommand;

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = "ai:audio2text
                            {audioFile : path to audio file}
                            {--l|language= : the language of the input audio. Supplying the input language in ISO-639-1 format will improve accuracy and latency.}
                           ";

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Transcribe an audio file to text';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(OpenAIService $openAIService)
    {
        if (!$this->checkOpenAI($openAIService)) {
            return;
        }

        //***** ARGUMENTS *****

        //audio file
        $audioFile = $this->argument('audioFile');
        if(!File::exists($audioFile)) {
            $this->error('audio file not found');
            return;
        }

        //language (optional)
        $language = $this->option('language');

        //***** QUERY *****

        $text = $openAIService->trascribe($audioFile, $language);

        //***** OUTPUT *****

        $this->info('Transcription:');
        $this->info($text);

        return;
    }

    /**
     * Define the command's schedule.
     *
     * @param Schedule $schedule
     * @return void
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }
}
