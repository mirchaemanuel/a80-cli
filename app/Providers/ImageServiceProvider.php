<?php

namespace App\Providers;

use App\Exceptions\OpenAIServiceV1;
use App\Services\AI\OpenAIService;
use App\Services\Images\Concretes\ImageServiceConcrete;
use App\Services\Images\Concretes\ImageServiceGD;
use App\Services\Images\Concretes\ImageServiceImagick;
use App\Services\Images\concretes\ImageServiceInterventionImpl;
use App\Services\Images\ImageDriver;
use App\Services\Images\ImageService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;

class ImageServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(ImageService::class, function ($app) {

            if (extension_loaded('imagick')) {
                return new ImageServiceInterventionImpl(ImageDriver::IMAGICK);
            }

            if (extension_loaded('gd')) {
                return new ImageServiceInterventionImpl(ImageDriver::GD);
            }

            return null;
        });
    }

    public function boot()
    {
    }
}
