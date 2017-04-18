<?php

namespace Famousinteractive\Translators;

use Illuminate\Support\ServiceProvider;

class TranslatorsServiceProvider extends ServiceProvider
{
    protected $commands = [
        'Famousinteractive\Translators\Commands\GenerateApiKey'
    ];

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/Config/famousTranslator.php' => config_path('famousTranslator.php'),
        ]);

        $this->loadMigrationsFrom(__DIR__.'/database/migrations/');
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
