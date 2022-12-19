<?php

namespace DeeRig\EnvBar\Tests\Feature\Middleware;

use DeeRig\EnvBar\Middleware\InjectBar;
use DeeRig\EnvBar\Tests\TestCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class InjectBarTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        File::copyDirectory(__DIR__ . '/../../../public', public_path('vendor/envbar'));
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        File::deleteDirectory(base_path('public/vendor'));
    }


    /** @test */
    public function it_should_not_inject_the_bar()
    {
        config()->set('envbar.enabled', false);

        $request    = Request::create('/');
        $middleware = new InjectBar();

        $response = $middleware->handle($request, function ($request) {
            return response(File::get(__DIR__ . '/../../fixtures/index.html'));
        });

        $this->assertStringNotContainsString('envbar-alert', $response->getContent());
    }

    /** @test */
    public function it_should_inject_the_bar()
    {
        config()->set('envbar.enabled', true);

        $request    = Request::create('/');
        $middleware = new InjectBar();

        $response = $middleware->handle($request, function ($request) {
            return response(File::get(__DIR__ . '/../../fixtures/index.html'));
        });

        $this->assertStringContainsString('envbar-alert', $response->getContent());
    }
}
