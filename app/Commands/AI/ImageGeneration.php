<?php

namespace App\Commands\AI;

use App\Enums\AI\OpenAIImageSize;
use App\Services\AI\OpenAIService;
use App\Traits\OpenAICommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Storage;
use LaravelZero\Framework\Commands\Command;

class ImageGeneration extends Command
{
    use OpenAICommand;

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = "ai:image
                           {prompt? : image prompt}
                           {imageName? : image name - if not set, date time will be used as name}
                           {--s|size=: [small, medium, large] default is medium}
                           {--c|count=: 1-10 default is 1}
                           ";

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Generate an image from a prompt';

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

        //image size
        $size = OpenAIImageSize::getSize($this->option('size'));
        if (!$size) {
            $this->info('size not defined, using medium');
            $size = OpenAIImageSize::MEDIUM;
        }

        //number of images
        $count = (int)$this->option('count');
        if (!$count) {
            $count = 1;
        } elseif ($count < 1 || $count > 10) {
            $this->error('count must be between 1 and 10');
            return;
        }

        //prompt
        $prompt = $this->argument('prompt');
        if (!$prompt) {
            $prompt = $this->ask('What\'s your prompt?');
        }

        //image name
        $imageName = $this->argument('imageName');
        if (!$imageName) {
            $imageName = date('Ymd-');
        }

        //***** QUERY *****
        $images = $openAIService->imageCreate($prompt, $size, $count);

        //***** OUTPUT *****
        if($images) {
            $this->info('saving images:...');
            foreach($images as $key => $image) {
                //saving image to file
                $filename = $imageName . $key . time() . '.png';
                Storage::disk('local')->put($filename, $image);
            }
        } else {
            $this->error('No images created');
        }

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
