<?php

namespace App\Commands\tools\images;

use App\Exceptions\ImageUtilsException;
use App\Services\Images\ImageService;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use LaravelZero\Framework\Commands\Command;
use function Termwind\render;

class ReadExifCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'tools:image:exif {imageFile}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Read EXIF data from an image file';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(ImageService $imageService)
    {
        /**
         * check if php exif support is enabled
         */
        if (!function_exists('exif_read_data')) {
            $this->error('PHP EXIF support is not enabled');
            return;
        }

        //check if file exists
        $imageName = $this->argument('imageFile');
        if (!File::exists($imageName)) {
            $this->error('File not found');
            return;
        }

        /**
         * check imageName is an image using File facade
         */
        if (!str_starts_with(File::mimeType($imageName), 'image/')) {
            $this->error('File is not an image');
            return;
        }

        /**
         * read exif data
         */
        try {
            $data = $imageService->readExif($imageName);

        } catch (ImageUtilsException $e) {
            $this->error($e->getMessage());
            return;
        }
        render(view('image-exif', [
            'imageName' => Storage::path($imageName),
            'exif_data' => $data
        ]));
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
