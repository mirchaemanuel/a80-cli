<?php

namespace App\Commands\AI;

use App\Services\AI\OpenAIService;
use App\Traits\OpenAICommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\File;
use LaravelZero\Framework\Commands\Command;

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

        //***** QUERY *****

        $audio = $openAIService->text2speech($text);
        file_put_contents('audio.mp3', $audio);

        //***** OUTPUT *****

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
