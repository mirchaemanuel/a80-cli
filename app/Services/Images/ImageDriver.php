<?php

namespace App\Services\Images;

enum ImageDriver: String
{
    case IMAGICK = 'imagick';
    case GD = 'gd';
}
