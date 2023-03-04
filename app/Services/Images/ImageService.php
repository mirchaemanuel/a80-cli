<?php

namespace App\Services\Images;

use App\Exceptions\ImageUtilsException;
use Intervention\Image\Exception\ImageException;
use Intervention\Image\ImageManager;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;

abstract class ImageService
{
    public const THUMB_PREFIX_NAME = 'thumb_';

    public function __construct(protected  ImageDriver $driver)
    {
    }

    public function getDriver(): ImageDriver
    {
        return $this->driver;
    }

    /*
     |--------------------------------------------------------------------------
     | IMAGE MANIPULATION METHODS
     |--------------------------------------------------------------------------
     */

    /**
     * Generate a thumbnail of an image
     *
     * @param string $imagePath the path of the image
     * @param string $outputPath the path where the thumbnail will be saved
     * @param int $width the width of the thumbnail
     * @return void the thumbnail is saved in the output path
     *
     * @throws ImageUtilsException if the image cannot be read or the thumbnail cannot be saved
     */
    abstract public function generateThumbnail(string $imagePath, string $outputPath, int $width): void;

    /*
    |--------------------------------------------------------------------------
    | IMAGE INFO METHODS
    |--------------------------------------------------------------------------
    */

    /**
     * Read the exif data of an image
     *
     * @param string $imagePath the path of the image
     * @return array the exif data of the image
     *
     * @throws ImageUtilsException if the image cannot be read
     */
    abstract public function readExif(string $imagePath): array;


    public function __toString(): string
    {
        return 'Driver:' . $this->driver->name;
    }

    /*
     |--------------------------------------------------------------------------
     | STATIC METHODS
     |--------------------------------------------------------------------------
     */

    /**
     * Generate the path for thumbnail of an image file
     *
     * @param string $dirname
     * @param string $filename
     *
     * @return string the path of the thumbnail
     */
    public
    static function generateThumbnailPath(string $dirname, string $filename): string
    {
        return $dirname . DIRECTORY_SEPARATOR . self::THUMB_PREFIX_NAME . $filename;
    }
}
