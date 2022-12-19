<?php

return [
    /**
     * Switch to true or false to enable or disable the package.
     */
    'enabled'      => env('ENVBAR_ENABLED', config('app.env') === 'production' ? false : true),

    /**
     * The source of the branch name to check.
     *
     * The available sources: git, github, bitbucket, gitlab, and envoyer.
     *
     * The git source will get the branch name from the project's .git/HEAD file.
     * The other sources will get the branch name using the API of the source.
     *
     * The GitHub, Bitbucket, and GitLab sources will get the branch name from the latest published tag.
     */
    'source'       => env('ENVBAR_SOURCE', 'git'),
    'sources'      => [
        'github'    => [
            'repository' => env('ENVBAR_GITHUB_REPOSITORY', null),
            'username'   => env('ENVBAR_GITHUB_USERNAME', null),
            'token'      => env('ENVBAR_GITHUB_TOKEN', null),
        ],
        'bitbucket' => [
            'workspace'  => env('ENVBAR_BITBUCKET_WORKSPACE', null),
            'repository' => env('ENVBAR_BITBUCKET_REPOSITORY', null),
            'username'   => env('ENVBAR_BITBUCKET_USERNAME', null),
            'token'      => env('ENVBAR_BITBUCKET_TOKEN', null),
        ],
        'gitlab'    => [
            'host'       => env('ENVBAR_GITLAB_HOST', null),
            'project_id' => env('ENVBAR_GITLAB_PROJECT_ID', null),
            'token'      => env('ENVBAR_GITLAB_TOKEN', null),
        ],
        'envoyer'   => [
            'project_id' => env('ENVBAR_ENVOYER_PROJECT_ID', null),
            'token'      => env('ENVBAR_ENVOYER_TOKEN', null),
        ],
    ],

    /**
     * The environment name will be retrieved from the APP_ENV variable.
     *
     * Below you will set the name of the environment (always in lowercase and slug), the color and if you want to show the alert.
     * Environment name example: 'Testing Development' will be 'testing-development'.
     *
     * The available colors are: blue, purple, green, red, yellow, amber, orange, indigo, and gray.
     */
    'environments' => [
        'local'   => [
            'color' => 'green',
        ],
        'sandbox' => [
            'color' => 'yellow',
        ],
        'qa'      => [
            'color' => 'amber',
        ],
        'staging' => [
            'color' => 'orange',
        ],
    ],
];
