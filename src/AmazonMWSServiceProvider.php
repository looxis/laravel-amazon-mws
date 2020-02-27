<?php

namespace Looxis\LaravelAmazonMWS;

use Illuminate\Support\ServiceProvider;

class AmazonMWSServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/amazon-mws.php' => config_path('amazon-mws.php'),
        ], 'config');
    }

    public function register()
    {
        $this->app->bind('amazon-mws', function () {
            return new MWSService();
        });

        $this->mergeConfigFrom(__DIR__.'/../config/amazon-mws.php', 'amazon-mws');
    }
}
