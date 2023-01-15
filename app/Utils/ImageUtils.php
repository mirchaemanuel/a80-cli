<?php

namespace App\Utils;

use App\Exceptions\ImageUtilsException;
use http\Encoding\Stream\Debrotli;
use Imagick;
use ImagickException;

abstract class ImageUtils
{
    public const THUMB_PREFIX_NAME = 'thumb_';

    /**
     * Generate image thumbnail
     *
     * @param string $fileName
     * @param int $width
     * @return string
     * @throws ImageUtilsException
     */
    public static function getImageThumbnail(string $fileName, int $width): string
    {
        try {
            $imagick = new Imagick($fileName);
            $imagick->thumbnailImage($width, $width, true, false);
            $imageBlob = $imagick->getImageBlob();
            if(!$imageBlob) {
                throw new ImageUtilsException('Unable to generate thumbnail for ' . $fileName);
            }
            return $imageBlob;
        } catch (ImagickException $e) {
            throw new ImageUtilsException('Imagick error: ' . $e->getMessage());
        }
    }

    #[ArrayShape(["x" => "float", "y" => "float"])]
    #[Pure]
    public static function getImageResolution(string $fileName) : array
    {
        try {
            $imagick = new Imagick($fileName);
            return $imagick->getImageResolution();
        } catch (ImagickException $e) {
            throw new ImageUtilsException('Imagick error: ' . $e->getMessage());
        }
    }

}
