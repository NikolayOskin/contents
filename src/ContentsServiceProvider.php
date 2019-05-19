<?php

namespace NikolayOskin\Contents;

use Illuminate\Support\ServiceProvider;

class ContentsServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/views', 'contents');

        if ($this->app->runningInConsole()) {

            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('contents.php'),
            ], 'config');

            // Publishing the views.
            $this->publishes([
                __DIR__.'/views' => resource_path('views/nikolay-oskin/contents'),
            ], 'views');

        }
    }

    public function register()
    {
        // Automatically apply the package configuration
        //$this->mergeConfigFrom(__DIR__.'/../config/config.php', 'contents');

        // Register the main class to use with the facade
        //$this->app->singleton('contents', function () {
        //    return new Contents;
        //});
    }
}
