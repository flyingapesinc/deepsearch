<?php

namespace FlyingApesInc\DeepSearch;
use Illuminate\Support\ServiceProvider;

class DeepSearchServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->make('FlyingApesInc\DeepSearch\DeepSearch');
    }
}
