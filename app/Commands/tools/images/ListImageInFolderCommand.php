<?php

namespace App\Commands\tools\images;

use App\Utils\ImageUtils;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\File;
use LaravelZero\Framework\Commands\Command;
use function Termwind\{render};


/**
 * ListImageInFolderCommand
 *
 * List all image file in a folder along with their size and info
 *
 * @todo add pipe command to thumbnail all images in a folder
 */
class ListImageInFolderCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'tools:image:list {folder}
                            {--r|recursive : List image in subfolder.}
                            {--o|output= : Output to a CSV file.}
                            {--gen-thumbnail : Generate thumbnail for all images.}
                            {--w|width= : Width of thumbnail}';

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
        if (!File::isDirectory($folder)) {
            $this->error('Folder not found');
            return;
        }

        /**
         * Get all files in folder
         */
        if ($this->option('recursive')) {
            $files = File::allFiles($folder);
        } else {
            $files = File::files($folder);
        }

        /**
         * Get all imagePaths in folder
         */
        $imagePaths = [];
        foreach ($files as $file) {
            $mimeType = File::mimeType($file);
            if (str_contains($mimeType, 'image')) {
                $imagePaths[$file->getPathInfo()->getRealPath()][] = [
                    'path'      => $file->getRealPath(),
                    'filename'  => $file->getFilename(),
                    'size'      => File::size($file),
                    'mimeType'  => $mimeType,
                    'extension' => $file->getExtension(),
                ];
            }
        }

        if (empty($imagePaths)) {
            $this->warn('No image found');
            return;
        }

        /**
         * Sort array imagePaths for key filename
         */
        foreach ($imagePaths as $key => $imagePath) {
            usort($imagePaths[$key], static function ($a, $b) {
                return strcasecmp($a['filename'], $b['filename']);
            });
        }

        /**
         * Output to CSV file
         */
        if ($this->option('output')) {
            $output = $this->option('output');
            // check if output is writable
            if (File::exists($output)) {
                $this->error('Output file  already exists');
                return;
            }

            $fp = fopen($output, 'wb');
            fputcsv($fp, ['path', 'filename', 'size', 'mimeType', 'extension']);
            foreach ($imagePaths as $key => $imagePath) {
                foreach ($imagePath as $path => $image) {
                    fputcsv($fp, $image);
                }
            }
            fclose($fp);
            $this->info('Output to CSV file: ' . $output);
        }

        render(view('image-list', [
            'imagePaths' => $imagePaths,
        ]));

        /**
         * Generate thumbnails
         */
        if ($this->option('gen-thumbnail')) {
            $this->info('Generating thumbnail...');
            $this->info(sprintf("- if image name starts with \"%s\" it will be skipped", ImageUtils::THUMB_PREFIX_NAME));
            $width = $this->option('width');
            if (!$width) {
                $width = 300;
            }
            $this->withProgressBar(array_merge(...array_values($imagePaths)), function ($image) use ($width) {
                //if filename start with thumbnail_ then skip
                if (str_starts_with($image['filename'], ImageUtils::THUMB_PREFIX_NAME)) {
                    return;
                }
                $this->call('tools:image:thumb', [
                    'filename'         => $image['path'],
                    'output'           => File::dirname($image['path']) . '/' . ImageUtils::THUMB_PREFIX_NAME . $image['filename'],
                    '--width'          => $width,
                    '--quiet'          => true,
                    '-q'               => true,
                    '--no-interaction' => true,
                ]);
            });
        }
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
