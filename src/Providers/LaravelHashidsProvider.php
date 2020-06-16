<?php

namespace Cirlmcesc\LaravelHashids\Providers;

use Illuminate\Support\ServiceProvider;
use Hashids\Hashids;

class LaravelHashidsServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var boolean
     */
    protected $defer = false;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . "/../../../config/hashids.php", "hashids");

        $this->app->singleton(Hashids::class, function () {
            return new Hashids(config('hashids.salt', config('app.key')), config('hashids.length', 16));
        });

        $this->app->singleton(LaravelHashids::class, function () {
            return new LaravelHashids(resolve(Hashids::class));
        });
    }

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * Commands
         */
        if ($this->app->runningInConsole()) {
            $this->commands([
                \Cirlmcesc\LaravelHashids\Commands\HashidsCommand::class,
                \Cirlmcesc\LaravelHashids\Commands\InstallCommand::class,
            ]);
        }

        /**
         * Config
         */
        $this->publishes([
            __DIR__ . "/../../../config/hashids.php" => config_path("hashids.php"),
        ], "hashids-config");
    }
}
