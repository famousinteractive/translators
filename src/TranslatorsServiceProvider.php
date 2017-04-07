<?php

namespace Famousinteractive\Translators;

use Illuminate\Support\ServiceProvider;

class TranslatorsServiceProvider extends ServiceProvider
{
    protected $commands = [
        'Famous\Translators\Commands\GenerateApiKey'
    ];


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
        include __DIR__.'/routes.php';

        $this->app->make('Famousinteractive\Translators\Controllers\ApiController');
        $this->commands($this->commands);
    }
}
