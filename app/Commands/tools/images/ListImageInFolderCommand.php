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
    protected $description = 'List all images in folder (and subfolders) with some info sorted by name';

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
         * Get all imagePaths in folder
         */
        $imagePaths = [];
        foreach($files as $file) {
            $mimeType = File::mimeType($file);
            if(str_contains($mimeType, 'image')) {
                $imagePaths[$file->getPathInfo()->getRealPath()][] = [
                    'path' => $file->getRealPath(),
                    'filename' => $file->getFilename(),
                    'size' => File::size($file),
                    'mimeType' => $mimeType,
                    'extension' => $file->getExtension(),
                ];
            }
        }

        if(empty($imagePaths)) {
            $this->warn('No image found');
            return;
        }

        /**
         * Sort array imagePaths for key filename
         */
        foreach($imagePaths as $key => $imagePath) {
            usort($imagePaths[$key], static function ($a, $b) {
                return strcasecmp($a['filename'], $b['filename']);
            });
        }

        render(view('image-list', [
            'imagePaths' => $imagePaths,
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
