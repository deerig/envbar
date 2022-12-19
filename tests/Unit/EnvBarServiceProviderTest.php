<?php

namespace DeeRig\EnvBar\Tests\Unit;

use DeeRig\EnvBar\Middleware\InjectBar;
use DeeRig\EnvBar\Tests\TestCase;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Support\Facades\Artisan;

class EnvBarServiceProviderTest extends TestCase
{
    /** @test */
    public function it_should_register_the_package__middleware()
    {
        $hasMiddleware = app(Kernel::class)->hasMiddleware(InjectBar::class);
        $this->assertTrue($hasMiddleware);
    }

    /** @test */
    public function it_should_register_the_package_translations()
    {
        $this->assertArrayHasKey('envbar', app(Translator::class)->getLoader()->namespaces());
    }

    /** @test */
    public function it_should_register_the_package_views()
    {
        $this->assertArrayHasKey('envbar', app()->get('view')->getFinder()->getHints());
    }

    /** @test */
    public function it_should_register_the_package_config()
    {
        $this->assertArrayHasKey('envbar', app()->get('config')->all());
    }

    /** @test */
    public function it_should_publish_the_configs()
    {
        Artisan::call('vendor:publish', [
            '--provider' => 'DeeRig\EnvBar\EnvBarServiceProvider',
            '--tag'      => 'config',
        ]);

        $configFile = config_path('envbar.php');

        $this->assertTrue(file_exists($configFile));

        unlink($configFile);
    }

    /** @test */
    public function it_should_publish_the_assets()
    {
        Artisan::call('vendor:publish', [
            '--provider' => 'DeeRig\EnvBar\EnvBarServiceProvider',
            '--tag'      => 'assets',
        ]);

        $cssFile      = public_path('vendor/envbar/css/app.css');
        $manifestFile = public_path('vendor/envbar/mix-manifest.json');

        $this->assertTrue(file_exists($cssFile));
        $this->assertTrue(file_exists($manifestFile));

        unlink($cssFile);
        unlink($manifestFile);
    }
}
