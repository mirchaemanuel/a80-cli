<?php

namespace App\Services\Images;

use App\Exceptions\ImageUtilsException;
use Intervention\Image\ImageManager;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;

class ImageService
{
    public const THUMB_PREFIX_NAME = 'thumb_';

    /**
     * @var ImageManager $imageManager the image manager instance
     */
    private readonly ImageManager $imageManager;

    /**
     * ImageService constructor.
     *
     * @param ImageDriver $driver the image driver (GD / Imagick)
     */
    public function __construct(private readonly ImageDriver $driver)
    {
        $this->imageManager = new ImageManager(['driver' => $this->driver->value]);
    }

    public function generateThumbnailPath(string $dirname, string $filename): string
    {
        return $dirname . DIRECTORY_SEPARATOR . self::THUMB_PREFIX_NAME . $filename;
    }

    public function getManager(): ImageManager
    {
        return $this->imageManager;
    }

    public function getDriver(): ImageDriver
    {
        return $this->driver;
    }

    public function __toString(): string
    {
        return 'Driver:' . $this->driver->name;
    }
}
