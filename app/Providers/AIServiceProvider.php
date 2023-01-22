<?php

namespace App\Providers;

use App\Services\AI\Concretes\OpenAIServiceV1;
use App\Services\AI\OpenAIService;
use Illuminate\Support\ServiceProvider;

class AIServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(OpenAIService::class, function ($app) {
            return ( new OpenAIServiceV1() );
        });
    }

    public function boot()
    {
    }
}
