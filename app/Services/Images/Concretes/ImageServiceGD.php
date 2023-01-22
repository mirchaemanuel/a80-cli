<?php

namespace App\Services\Images\Concretes;

use App\Exceptions\ImageUtilsException;
use App\Services\Images\ImageService;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;
use Spatie\Image\Exceptions\InvalidManipulation;
use Spatie\Image\Image;

class ImageServiceGD implements ImageService
{

    public function getThumbnail(string $fileName, int $width, string $output)
    {
        try {
            Image::load($fileName)
                ->width($width)
                ->save($output);
        } catch (InvalidManipulation | \Exception $e) {
            throw new ImageUtilsException('GD manipulation error: ' . $e->getMessage());
        }
    }

    #[ArrayShape( ["x" => "float", "y" => "float"] )]
    #[Pure]
    public function getImageResolution(string $fileName): array
    {
        Image::load($fileName);
        return [
            'x' => Image::getWidth(),
            'y' => Image::getHeight()
        ];
    }
}
