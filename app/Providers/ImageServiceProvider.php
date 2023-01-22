<?php

namespace App\Providers;

use App\Services\AI\Concretes\OpenAIServiceV1;
use App\Services\AI\OpenAIService;
use App\Services\Images\Concretes\ImageServiceGD;
use App\Services\Images\Concretes\ImageServiceImagick;
use App\Services\Images\ImageService;
use Illuminate\Support\ServiceProvider;

class ImageServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(ImageService::class, function ($app) {

            if (extension_loaded('imagick')) {
                return new ImageServiceImagick();
            }

            if (extension_loaded('gd')) {
                return new ImageServiceGD();
            }

            return null;
        });
    }

    public function boot()
    {
    }
}
