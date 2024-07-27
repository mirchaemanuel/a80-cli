<?php

namespace App\Commands\AI;

use App\Services\AI\OpenAIService;
use App\Traits\OpenAICommand;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
use function Laravel\Prompts\select;

class Text2Speech extends Command
{
    use OpenAICommand;

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = "ai:text2speech ";

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Convert text to speech';

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

        // ask the user for the text
        $text = $this->ask('Enter the text to convert to speech');

        $voice = select(
            'Select the voice',
            [
                'alloy',
                'echo', 'fable', 'onyx', 'nova', 'shimmer'
            ],
            scroll: 10
        );

        $quality = select(
            'Select the quality',
            [
                'tts-1' => 'standard',
                'tts-1-hd' => 'high quality',
            ],
            scroll: 2
        );

        // ask for file name
        $fileName = $this->ask('Enter the file name');

        //***** QUERY *****

        $audio = $openAIService->text2speech($text, voice: $voice, model: $quality);

        file_put_contents($fileName. '.mp3', $audio);

        //***** OUTPUT *****

        return;
    }

    /**
     * Define the command's schedule .
     *
     * @param Schedule $schedule
    * @return void
        */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }
}
