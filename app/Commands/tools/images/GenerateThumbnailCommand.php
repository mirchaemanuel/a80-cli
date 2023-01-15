<?php

namespace App\Commands\tools\images;

use App\Exceptions\ImageUtilsException;
use App\Utils\ImageUtils;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use LaravelZero\Framework\Commands\Command;

class GenerateThumbnailCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'tools:image:thumb
                            {imageName : path of the image}
                            {output? : output filename. If omitted will be saved in the same folder with prefix "thumb_"}
                            {--w|width= : width of the thumbnail}
                            {--f|force= : overwrite existing file}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Generate a thumbnail of an image with a given width';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //check if imagick is installed
        if (!extension_loaded('imagick')) {
            $this->error('Imagick extension is not installed');
            $this->info('Please install Imagick extension');
            $this->info('https://www.php.net/manual/en/imagick.installation.php');
            return;
        }

        //check if file exists
        $imageName = $this->argument('imageName');
        if (!File::exists($imageName)) {
            $this->error('File not found');
            return;
        }

        //get width and height
        $width = $this->option('width');
        if (!$width) {
            $this->warn('Width not specified, using default value of 300');
            $width = 300;
        }

        //get output imageName
        $output = $this->argument('output');
        if (!$output) {
            $output = File::dirname($imageName) . '/' . ImageUtils::THUMB_PREFIX_NAME . File::basename($imageName);
            $this->warn(sprintf("No output imageName specified. %s will be used", $output));
        }
        if (File::exists($output) && !$this->hasOption('force')) {
            $this->error('Output file already exists');
            $this->info('Use --force to overwrite');
            return;
        }

        /**
         * check imageName is an image using File facade
         */
        if (!str_starts_with(File::mimeType($imageName), 'image/')) {
            $this->error('File is not an image');
            return;
        }

        try {
            $outputBlob = ImageUtils::getImageThumbnail($imageName, $width);
            File::put($output, $outputBlob);
            if (!$this->option('quiet')) {
                $this->info('Thumbnail generated in ' . $output);
            }
        } catch (ImageUtilsException $e) {
            $this->error($e->getMessage());
            return false;
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
