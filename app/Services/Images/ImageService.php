<?php

namespace App\Services\Images;

use App\Exceptions\ImageUtilsException;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;

interface ImageService
{
    public const THUMB_PREFIX_NAME = 'thumb_';

    /**
     * Generate image thumbnail
     *
     * @param string $fileName
     * @param int $width
     * @return string
     *
     * @throws ImageUtilsException
     */
    public function getThumbnail(string $fileName, int $width, string $output);

    #[ArrayShape( ["x" => "float", "y" => "float"] )]
    #[Pure]
    public function getImageResolution(string $fileName): array;

}
