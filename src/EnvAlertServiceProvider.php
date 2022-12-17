<?php
namespace DeeRig\EnvAlert;

use DeeRig\EnvAlert\Middleware\InjectBar;
use DeeRig\EnvAlert\View\Components\EnvAlert;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Support\ServiceProvider;

class EnvAlertServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'envalert');
    }

    public function boot()
    {
        $this->registerMiddleware(InjectBar::class);

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'envalert');

        if ($this->app->runningInConsole()) {
            $this->publishAssets();
            $this->publishConfig();
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
            __DIR__.'/../public' => public_path('vendor/envalert'),
        ], 'assets');
    }

    protected function publishConfig()
    {
        $this->publishes([
            __DIR__.'/../config/config.php' => config_path('envalert.php'),
        ], 'config');
    }
}
