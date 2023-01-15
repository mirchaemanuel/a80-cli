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
    protected $signature = 'tools:image:thumbnail
                            {filename : filename of the image}
                            {output : output filename}
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
        $filename = $this->argument('filename');
        if (!File::exists($filename)) {
            $this->error('File not found');
            return;
        }

        //get width and height
        $width = $this->option('width');
        if(!$width) {
            $this->warn('Width not specified, using default value of 300');
            $width = 300;
        }

        //get output filename
        $output = $this->argument('output');
        if(!$output){
            $this->error('Output filename is required');
            return;
        }
        if(File::exists($output) && !$this->hasOption('force')){
            $this->error('Output file already exists');
            $this->info('Use --force to overwrite');
            return;
        }

        /**
         * check filename is an image using File facade
         */
        if(!str_starts_with(File::mimeType($filename), 'image/')){
            $this->error('File is not an image');
            return;
        }

        $this->task('generating thumbnail', function () use ($filename, $width, $output) {
            try {
                $outputBlob = ImageUtils::getImageThumbnail($filename, $width);
                File::put($output, $outputBlob);
                $this->info('Thumbnail generated in ' . $output);
                return true;
            } catch (ImageUtilsException $e) {
                $this->error($e->getMessage());
                return false;
            }
        });

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
