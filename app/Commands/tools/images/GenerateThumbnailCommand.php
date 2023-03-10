<?php

namespace App\Commands\tools\images;

use App\Exceptions\ImageUtilsException;
use App\Services\Images\Concretes\ImageServiceConcrete;
use App\Services\Images\ImageService;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Exception\ImageException;
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
    public function handle(ImageService $imageService): void
    {
        if (!$this->checkPrerequisites()) {
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
            $output = ImageService::generateThumbnailPath(File::dirname($imageName), File::basename($imageName));
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
            $imageService->generateThumbnail($imageName, $output, $width);

            if (!$this->option('quiet')) {
                $this->info('Thumbnail generated in ' . $output);
            }
        } catch (ImageUtilsException $e) {
            $this->error($e->getMessage());
            return;
        }

    }

    private function checkPrerequisites(): bool
    {
        if (!extension_loaded('imagick') && !extension_loaded('gd')) {
            $this->error('please install GD extension or Imagick extension');
            $this->info('Imagick is recommended');
            $this->info('https://www.php.net/manual/en/image.installation.php');
            $this->info('https://www.php.net/manual/en/imagick.installation.php');
            return false;
        }
        return true;
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
