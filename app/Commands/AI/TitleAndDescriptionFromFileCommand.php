<?php

namespace App\Commands\AI;

use App\Enums\AI\OpenAIModel;
use App\Services\AI\OpenAIService;
use App\Traits\OpenAICommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\File;
use LaravelZero\Framework\Commands\Command;
use OpenAI;

class TitleAndDescriptionFromFileCommand extends Command
{
    use OpenAICommand;

    const MAX_SIZE = 1024*10;
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'ai:title-abstract
                           {filename : file to read}
                           {--m|max-tokens= : [1-4000] default is 2000}
                           {--model= : [davinci, ada, babbage, curie, content-filter-alpha-c4] default is davinci}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Generate a title and description from a text file';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(OpenAIService $openAIService)
    {
        if(!$this->checkOpenAI($openAIService)){
            return;
        }

        //***** ARGUMENTS *****

        //read the file name
        $filename = $this->argument('filename');
        if (!file_exists($filename)) {
            $this->error('File not found');
            return;
        }

        //max tokens
        $maxTokens = (int)$this->option('max-tokens');
        if(!$maxTokens){
            $maxTokens = 2000;
        }elseif($maxTokens < 1 || $maxTokens > 4000){
            $this->error('max-tokens must be between 1 and 4000');
            return;
        }

        //check file is not too big
        $fileSize = File::size($filename);
        if ($fileSize > self::MAX_SIZE) {
            $this->error('File is too big');
            return;
        }elseif ($fileSize === 0) {
            $this->error('File is empty');
            return;
        }

        //check is a text file
        $mimeTypes = File::mimeType($filename);
        if($mimeTypes !== 'text/plain'){
            $this->error('File is not a text file');
            return;
        }

        //read the file
        $fileContent = File::get($filename);

        //check the model
        $model = OpenAIModel::getModel($this->option('model'));
        if(!$model) {
            $this->info('Using default model: davinci');
            $model = OpenAIModel::davinci;
        }
        //***** QUERY *****

        //prompt OpenAI
        $response = $openAIService->prompt(
            <<<TXT
Provide a short title for the article, properly formatted for SEO. Also provide an abstract of the article. Both in the same language of the article.
Article: ${fileContent}
TXT, $maxTokens, $model);

        //***** OUTPUT *****
        $this->info('Title: ' . $response->choices[0]->text);

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
