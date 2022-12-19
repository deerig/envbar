{{--<link href="{{ mix('/css/app.css', 'vendor/envbar') }}" rel="stylesheet" />--}}

@php
    use DeeRig\EnvBar\EnvBar;
    use Illuminate\Support\Str;

    $envName = Str::title(Str::replace(['-', '_'], ' ', config('app.env')));
    $env = Str::lower(Str::slug(config('app.env')));
    $envColor = config("envbar.environments.{$env}.color") ?? 'blue';
    $envBar = (new EnvBar());
    $branch = $envBar->getVersion();
    $isRelease = $envBar->isRelease();
@endphp

<div @class([
        'envbar-py-3 envbar-px-4 envbar-text-base envbar-flex envbar-items-center',
        'envbar-border-l-6 envbar-border-blue-500 envbar-bg-blue-100 envbar-text-blue-700' => $envColor === 'blue',
        'envbar-border-l-6 envbar-border-purple-500 envbar-bg-purple-100 envbar-text-purple-700' => $envColor === 'purple',
        'envbar-border-l-6 envbar-border-green-500 envbar-bg-green-100 envbar-text-green-700' => $envColor === 'green',
        'envbar-border-l-6 envbar-border-red-500 envbar-bg-red-100 envbar-text-red-700' => $envColor === 'red',
        'envbar-border-l-6 envbar-border-yellow-500 envbar-bg-yellow-100 envbar-text-yellow-700' => $envColor === 'yellow',
        'envbar-border-l-6 envbar-border-amber-500 envbar-bg-amber-100 envbar-text-amber-700' => $envColor === 'amber',
        'envbar-border-l-6 envbar-border-orange-500 envbar-bg-orange-100 envbar-text-orange-700' => $envColor === 'orange',
        'envbar-border-l-6 envbar-border-indigo-500 envbar-bg-indigo-100 envbar-text-indigo-700' => $envColor === 'indigo',
        'envbar-border-l-6 envbar-border-gray-500 envbar-bg-gray-300 envbar-text-gray-800' => $envColor === 'gray'
    ]) role="envbar-alert">
    @lang('envbar::messages.you-are-in-the')
    <x-envbar::badge :color="$envColor">{{ $envName }}</x-envbar::badge>
    @lang('envbar::messages.environment')
    <x-envbar::branch class="envbar-w-5 envbar-h-5 envbar-mx-1" :color="$envColor"/>
    @lang('envbar::messages.the-current-source-is', ['source' => $isRelease ? 'release' : 'branch'])
    <x-envbar::badge :color="$envColor">{{ $branch }}</x-envbar::badge>
</div>
