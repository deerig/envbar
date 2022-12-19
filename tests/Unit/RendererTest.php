<?php

namespace DeeRig\EnvBar\Tests\Unit;

use DeeRig\EnvBar\Renderer;
use DeeRig\EnvBar\Tests\TestCase;
use Illuminate\Foundation\Testing\Concerns\InteractsWithViews;
use Illuminate\Support\Facades\File;

class RendererTest extends TestCase
{
    use InteractsWithViews;

    /** @var Renderer */
    public $renderer;

    /** @var string */
    public $cssId;

    public function setUp(): void
    {
        parent::setUp();
        $this->renderer = new Renderer();

        File::copyDirectory(__DIR__ . '/../../public', public_path('vendor/envbar'));
        $this->cssId = json_decode(File::get(__DIR__ . '/../../public/mix-manifest.json'), JSON_PRETTY_PRINT);
        $this->cssId = '/vendor/envbar' . $this->cssId['/css/app.css'];
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        File::deleteDirectory(base_path('public/vendor'));
    }

    /** @test */
    public function it_should_return_the_link_tag()
    {
        $head = $this->renderer->renderHead();
        $this->assertEquals('<link href="' . $this->cssId . '" rel="stylesheet" />', $head);
    }

    /** @test */
    public function it_should_return_the_view_component()
    {
        $view = $this->renderer->renderBar();
        $this->assertEquals('envbar::components.bar', $view->name());
    }
}
