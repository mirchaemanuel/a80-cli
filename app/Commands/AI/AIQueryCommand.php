<?php

namespace App\Commands\AI;

use App\Services\AI\OpenAIService;
use App\Traits\OpenAICommand;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class AIQueryCommand extends Command
{
    use OpenAICommand;

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'ai:query
                           {question? : question to ask}
                           {--m|max-tokens= : [1-4000] default is 2000}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'place a question to OpenAI (using da-vinci)';

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

        //max tokens
        $maxTokens = (int)$this->option('max-tokens');
        if (!$maxTokens) {
            $maxTokens = 2000;
        } elseif ($maxTokens < 1 || $maxTokens > 4000) {
            $this->error('max-tokens must be between 1 and 4000');
            return;
        }

        //question
        $question = $this->argument('question');
        if (!$question) {
            $question = $this->ask('What\'s your question?');
        }

        //***** QUERY *****

        $response = $openAIService->prompt($question, $maxTokens);
        //***** OUTPUT *****

        //check output exists
        if (!isset($response['choices'][0]['message'])) {
            $this->error('No answer found');
            return;
        }

        $this->info($response['choices'][0]['message']['content']);
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
