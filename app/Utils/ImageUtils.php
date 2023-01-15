<?php

namespace App\Utils;

use App\Exceptions\ImageUtilsException;
use Imagick;
use ImagickException;

abstract class ImageUtils
{

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
            return $imagick->getImageBlob();
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
