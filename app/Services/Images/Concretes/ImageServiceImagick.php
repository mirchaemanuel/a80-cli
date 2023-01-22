<?php

namespace App\Services\Images\Concretes;

use App\Exceptions\ImageUtilsException;
use App\Services\Images\ImageService;
use Illuminate\Support\Facades\File;
use Imagick;
use ImagickException as ImagickExceptionAlias;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;

class ImageServiceImagick implements ImageService
{

    /**
     * Generate image thumbnail
     *
     * @param string $fileName
     * @param int $width
     * @return string
     * @throws ImageUtilsException
     */
    public  function getThumbnail(string $fileName, int $width, string $output)
    {
        try {
            $imagick = new Imagick($fileName);
            $imagick->thumbnailImage($width, $width, true, false);
            $imageBlob = $imagick->getImageBlob();
            if(!$imageBlob) {
                throw new ImageUtilsException('Unable to generate thumbnail for ' . $fileName);
            }

            File::put($output, $imageBlob);
        } catch (ImagickExceptionAlias $e) {
            throw new ImageUtilsException('Imagick error: ' . $e->getMessage());
        }
    }

    #[ArrayShape(["x" => "float", "y" => "float"])]
    #[Pure]
    public  function getImageResolution(string $fileName) : array
    {
        try {
            $imagick = new Imagick($fileName);
            return $imagick->getImageResolution();
        } catch (ImagickExceptionAlias $e) {
            throw new ImageUtilsException('Imagick error: ' . $e->getMessage());
        }
    }
}
