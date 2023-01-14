<?php

namespace App\Commands\AI;

use App\Utils\CheckDotEnv;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
use OpenAI;

class Query extends Command
{
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
    public function handle()
    {
        //check if .env file exists, otherwise create it
        if (CheckDotEnv::exists() === false) {
            $this->error('.env file is not present');
            $this->info('I\'m creating a .env file for you');
            CheckDotEnv::create();
            $this->info('Please edit the `.env` file and add your OpenAI API key');
            $this->info('You can get your API key from https://beta.openai.com/account/api-keys');
            return;
        }

        //check .env file
        $openaiKey = config('ai.openai.api_key');
        if (empty($openaiKey)) {
            $this->error('OpenAI API key is not present in .env file');
            $this->info('Create a .env file in the same directory of a80 and add the following line:');
            $this->info('OPENAI_API_KEY=your_api_key');
            $this->info('You can get your API key from https://beta.openai.com/account/api-keys');
            return;
        }

        //***** ARGUMENTS *****

        //max tokens
        $maxTokens = (int)$this->option('max-tokens');
        if(!$maxTokens){
            $maxTokens = 2000;
        }elseif($maxTokens < 1 || $maxTokens > 4000){
            $this->error('max-tokens must be between 1 and 4000');
            return;
        }

        //question
        $question = $this->argument('question');
        if(!$question){
            $question = $this->ask('What\'s your question?');
        }

        //***** QUERY *****

        $openai = OpenAI::client($openaiKey);
        $response = $openai->completions()->create([
            'model' => 'text-davinci-003',
            'prompt' => $question,
            'max_tokens' => $maxTokens,
            'temperature' => 0.9,
        ]);

        //***** OUTPUT *****

        //check output exists
        if(!isset($response['choices'][0]['text'])){
            $this->error('No answer found');
            return;
        }

        $this->info($response['choices'][0]['text']);
    }

    /**
     * Define the command's schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }
}
