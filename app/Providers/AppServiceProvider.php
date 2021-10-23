<?php

namespace App\Providers;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        JsonResource::withoutWrapping();
        /*$this->app->singleton('languages', function () {
            if (Cache::missing('languages'))
                return Cache::rememberForever('languages', function () {
                    return Language::all();
                });
            return Cache::get('languages', function () {
                return Language::all();
            });
        });*/
    }
}
