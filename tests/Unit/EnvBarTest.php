<?php

namespace DeeRig\EnvBar\Tests\Unit;

use DeeRig\EnvBar\EnvBar;
use DeeRig\EnvBar\Tests\TestCase;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class EnvBarTest extends TestCase
{
    /** @var EnvBar */
    public $envBar;

    public function setUp(): void
    {
        parent::setUp();
        $this->envBar = new EnvBar();
    }

    /** @test */
    public function it_should_return_the_git_version()
    {
        config()->set('envbar.source', 'git');

        File::makeDirectory(base_path('.git'));
        File::put(base_path('.git/HEAD'), 'ref: refs/heads/master');

        $this->assertEquals('master', $this->envBar->getVersion());

        File::deleteDirectory(base_path('.git'));
    }

    /** @test */
    public function it_should_return_the_last_github_release()
    {
        config()->set('envbar.source', 'github');
        config()->set('envbar.sources.github.repository', 'testing');
        config()->set('envbar.sources.github.username', 'testing');
        config()->set('envbar.sources.github.token', Str::random());

        $response = json_decode(File::get(__DIR__ . '/../fixtures/github.json'), JSON_PRETTY_PRINT);
        Http::fake([
            'api.github.com/*' => Http::response($response),
        ]);

        $this->assertEquals('1.0.0', $this->envBar->getVersion());
    }

    /** @test */
    public function it_should_return_the_last_bitbucket_release()
    {
        config()->set('envbar.source', 'bitbucket');
        config()->set('envbar.sources.bitbucket.workspace', 'testing');
        config()->set('envbar.sources.bitbucket.repository', 'testing');
        config()->set('envbar.sources.bitbucket.username', 'testing');
        config()->set('envbar.sources.bitbucket.token', Str::random());

        $response = json_decode(File::get(__DIR__ . '/../fixtures/bitbucket.json'), JSON_PRETTY_PRINT);
        Http::fake([
            'api.bitbucket.org/*' => Http::response($response),
        ]);

        $this->assertEquals('1.0.0', $this->envBar->getVersion());
    }

    /** @test */
    public function it_should_return_the_last_gitlab_release()
    {
        config()->set('envbar.source', 'gitlab');
        config()->set('envbar.sources.gitlab.host', 'gitlab.example.com');
        config()->set('envbar.sources.gitlab.project_id', 'testing');
        config()->set('envbar.sources.gitlab.token', Str::random());

        $response = json_decode(File::get(__DIR__ . '/../fixtures/gitlab.json'), JSON_PRETTY_PRINT);
        Http::fake([
            'gitlab.example.com/*' => Http::response($response),
        ]);

        $this->assertEquals('1.0.0', $this->envBar->getVersion());
    }

    /** @test */
    public function it_should_return_the_last_envoyer_deployed_branch_or_tag()
    {
        config()->set('envbar.source', 'envoyer');
        config()->set('envbar.sources.envoyer.project_id', 'testing');
        config()->set('envbar.sources.envoyer.token', Str::random());

        $response = json_decode(File::get(__DIR__ . '/../fixtures/envoyer.json'), JSON_PRETTY_PRINT);
        Http::fake([
            'envoyer.io/*' => Http::response($response),
        ]);

        $this->assertEquals('master', $this->envBar->getVersion());
    }
}
