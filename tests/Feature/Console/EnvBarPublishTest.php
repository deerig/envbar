<?php

namespace DeeRig\EnvBar\Tests\Feature\Console;

use DeeRig\EnvBar\Tests\TestCase;
use Illuminate\Support\Facades\File;

class EnvBarPublishTest extends TestCase
{
    /** @test */
    public function it_should_publish_the_assets()
    {
        $this->artisan('envbar:publish');

        $cssFile      = public_path('vendor/envbar/css/app.css');
        $manifestFile = public_path('vendor/envbar/mix-manifest.json');

        $this->assertTrue(File::exists($cssFile));
        $this->assertTrue(File::exists($manifestFile));

        File::delete($cssFile);
        File::delete($manifestFile);
    }
}
