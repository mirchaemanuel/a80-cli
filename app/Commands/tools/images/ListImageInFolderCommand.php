<?php

namespace App\Commands\tools\images;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\File;
use LaravelZero\Framework\Commands\Command;
use function Termwind\{render};

class ListImageInFolderCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'tools:image:list {folder}
                            {--r|recursive : List image in subfolder.}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'List all images in folder (and subfolders) with some info';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        /**
         * Get folder path
         */
        $folder = $this->argument('folder');
        if(!File::isDirectory($folder)) {
            $this->error('Folder not found');
            return;
        }

        /**
         * Get all files in folder
         */
        if($this->option('recursive')) {
            $files = File::allFiles($folder);
        } else {
            $files = File::files($folder);
        }

        /**
         * Get all images in folder
         */
        $images = [];
        foreach($files as $file) {
            $mimeType = File::mimeType($file);
            if(str_contains($mimeType, 'image')) {
                $images[$file->getPath()][] = [
                    'path' => $file->getRealPath(),
                    'filename' => $file->getFilename(),
                    'size' => File::size($file),
                    'mimeType' => $mimeType,
                    'extension' => $file->getExtension(),
                ];
            }
        }

        if(empty($images)) {
            $this->warn('No image found');
            return;
        }

        render(view('image-list', [
            'images' => $images,
        ]));

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
