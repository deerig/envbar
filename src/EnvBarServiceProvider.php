<?php

namespace DeeRig\EnvBar;

use DeeRig\EnvBar\Console\EnvBarPublish;
use DeeRig\EnvBar\Middleware\InjectBar;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Support\ServiceProvider;

class EnvBarServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'envbar');
    }

    public function boot()
    {
        $this->registerMiddleware(InjectBar::class);

        $this->loadTranslationsFrom(__DIR__ . '/../lang', 'envbar');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'envbar');

        if ($this->app->runningInConsole()) {
            $this->publishAssets();
            $this->publishConfig();
            $this->publishCommands();
        }
    }

    protected function registerMiddleware($middleware)
    {
        $kernel = $this->app[Kernel::class];
        $kernel->pushMiddleware($middleware);
    }

    protected function publishAssets()
    {
        $this->publishes([
            __DIR__ . '/../public' => public_path('vendor/envbar'),
        ], 'assets');
    }

    protected function publishConfig()
    {
        $this->publishes([
            __DIR__ . '/../config/config.php' => config_path('envbar.php'),
        ], 'config');
    }

    public function publishCommands()
    {
        $this->commands([
            EnvBarPublish::class,
        ]);
    }
}
