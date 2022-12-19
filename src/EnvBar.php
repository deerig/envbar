<?php

namespace DeeRig\EnvBar;

use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class EnvBar
{
    /**
     * @return string|null
     * @throws Exception
     */
    public function getVersion(): ?string
    {
        $source = config('envbar.source');

        switch ($source) {
            case 'git':
                return $this->getGitVersion();
            case 'github':
                return $this->getGitHubVersion();
            case 'bitbucket':
                return $this->getBitbucketVersion();
            case 'gitlab':
                return $this->getGitLabVersion();
            case 'envoyer':
                return $this->getEnvoyerVersion();
            default:
                return null;
        }
    }

    public function isRelease(): bool
    {
        $source = config('envbar.source');

        return in_array($source, ['github', 'bitbucket', 'gitlab']);
    }

    /**
     * @throws Exception
     */
    private function validateSettings(array $settings): void
    {
        foreach ($settings as $key => $setting) {
            if (!$setting) {
                throw new Exception("The {$key} is not set in the config file.");
            }
        }
    }

    private function getGitVersion(): ?string
    {
        return Cache::rememberForever('envbar::git::version', function () {
            $path = base_path('.git/HEAD');
            if (!File::exists($path)) {
                return null;
            }

            $branch = File::get($path);
            if (Str::contains($branch, 'ref: refs/heads/')) {
                return Str::replace('ref: refs/heads/', '', $branch);
            }

            return null;
        });
    }

    /**
     * @throws Exception
     */
    private function getGitHubVersion(): ?string
    {
        $settings = config('envbar.sources.github');
        $this->validateSettings($settings);

        return Cache::rememberForever('envbar::github::version', function () use ($settings) {
            $response = Http::withBasicAuth($settings['username'], $settings['token'])
                ->get('https://api.github.com/repos/' . $settings['repository'] . '/tags');

            if ($response->ok()) {
                $tags    = $response->collect();
                $lastTag = $tags->first();

                return $lastTag['name'];
            }

            return null;
        });
    }

    private function getBitbucketVersion(): ?string
    {
        $settings = config('envbar.sources.bitbucket');
        $this->validateSettings($settings);

        return Cache::rememberForever('envbar::bitbucket::version', function () use ($settings) {
            $response = Http::withToken('Bearer ' . $settings['token'])
                ->get('https://api.bitbucket.org/2.0/repositories/' . $settings['workspace'] . '/' . $settings['repository'] . '/refs/tags',
                    [
                        'sort' => 'target.date',
                    ]);

            if ($response->ok()) {
                $tags    = $response->collect();
                $lastTag = $tags->get('values')->first();

                return $lastTag['name'];
            }

            return null;
        });
    }

    /**
     * @throws Exception
     */
    private function getGitLabVersion(): ?string
    {
        $settings = config('envbar.sources.gitlab');
        if (!$settings['host']) {
            throw new Exception("The host is not set in the config file.");
        }

        if (!$settings['project_id']) {
            throw new Exception("The project_id is not set in the config file.");
        }


        return Cache::rememberForever('envbar::gitlab::version', function () use ($settings) {
            $response = Http::withHeaders(['PRIVATE-TOKEN' => $settings['token']])
                ->get($settings['host'] . '/api/v4/projects/' . $settings['project_id'] . '/repository/tags');

            if ($response->ok()) {
                $tags    = $response->collect();
                $lastTag = $tags->first();

                return $lastTag['release']['tag_name'];
            }

            return null;
        });
    }

    /**
     * @throws Exception
     */
    private function getEnvoyerVersion(): ?string
    {
        $settings = config('envbar.sources.envoyer');
        $this->validateSettings($settings);

        return Cache::rememberForever('envbar::envoyer::version', function () use ($settings) {
            $response = Http::withToken('Bearer ' . $settings['token'])
                ->get('https://envoyer.io/api/projects/' . $settings['project_id']);

            return data_get($response->json(), 'project.last_deployed_branch');
        });
    }
}
