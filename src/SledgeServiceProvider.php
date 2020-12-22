<?php
namespace MiladZamir\Sledge;

use Illuminate\Support\ServiceProvider;

class SledgeServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/sledge'),
            ], 'views');
            $this->publishes([
                __DIR__ . '/../config/sledge.php' => config_path('sledge.php'),
            ]);

        }
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'sledge');
    }

    public function register()
    {

    }
}
