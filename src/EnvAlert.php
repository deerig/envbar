<?php

namespace DeeRig\EnvAlert;

use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class EnvAlert
{
    /**
     * @return string|null
     * @throws Exception
     */
    public function getVersion(): string | null
    {
        $source = config('envalert.source');

        return match($source) {
            'git' => $this->getGitVersion(),
            'github' => $this->getGitHubVersion(),
            'bitbucket' => $this->getBitbucketVersion(),
            'gitlab' => $this->getGitLabVersion(),
            'envoyer' => $this->getEnvoyerVersion(),
            default => null
        };
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
        return Cache::rememberForever('envalert::git::version', function () {
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
        $settings = config('envalert.sources.github');
        $this->validateSettings($settings);

        return Cache::rememberForever('envalert::github::version', function () use ($settings) {
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
        $settings = config('envalert.sources.bitbucket');
        $this->validateSettings($settings);

        return Cache::rememberForever('envalert::bitbucket::version', function () use ($settings) {
            $response = Http::withToken('Bearer ' . $settings['token'])
                ->get('https://api.bitbucket.org/2.0/repositories/' . $settings['workspace'] . '/' . $settings['repository'] . '/refs/tags', [
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
        $settings = config('envalert.sources.gitlab');
        if (!$settings['host']) {
            throw new Exception("The host is not set in the config file.");
        }

        if (!$settings['project_id']) {
            throw new Exception("The project_id is not set in the config file.");
        }


        return Cache::rememberForever('envalert::gitlab::version', function () use ($settings) {
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
        $settings = config('envalert.sources.envoyer');
        $this->validateSettings($settings);

        return Cache::rememberForever('envalert::envoyer::version', function () use ($settings) {
            $response = Http::withToken('Bearer ' . $settings['token'])
                ->get('https://envoyer.io/api/projects/' . $settings['project_id']);

            return data_get($response->json(), 'project.last_deployed_branch');
        });
    }
}
