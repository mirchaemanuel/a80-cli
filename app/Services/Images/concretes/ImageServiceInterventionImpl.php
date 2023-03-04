<?php

namespace App\Services\Images\concretes;

use App\Exceptions\ImageUtilsException;
use App\Services\Images\ImageDriver;
use App\Services\Images\ImageService;
use Intervention\Image\Exception\ImageException;
use Intervention\Image\ImageManager;

class ImageServiceInterventionImpl extends ImageService
{
    /**
     * @var ImageManager $imageManager the image manager instance
     */
    private readonly ImageManager $imageManager;

    /**
     * ImageService constructor.
     *
     * @param ImageDriver $driver the image driver (GD / Imagick)
     */
    public function __construct(protected ImageDriver $driver)
    {
        parent::__construct($driver);
        $this->imageManager = new ImageManager(['driver' => $this->driver->value]);
    }

    public function generateThumbnail(string $imagePath, string $outputPath, int $width): void
    {
        try {
            $image = $this->imageManager->make($imagePath);
            $image->resize($width, null, function ($constraint) {
                $constraint->aspectRatio();
            });
            $image->save($outputPath);
            $image->destroy();
        } catch (ImageException $e) {
            throw new ImageUtilsException($e->getMessage());
        }
    }

    public function readExif(string $imagePath): array
    {
        try {
            $image = $this->imageManager->make($imagePath);
            $exif = $image->exif();
            $image->destroy();
            return $exif;
        } catch (ImageException $e) {
            throw new ImageUtilsException($e->getMessage());
        }
    }


    protected function getManager(): ImageManager
    {
        return $this->imageManager;
    }
}
