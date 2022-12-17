{{--<link href="{{ mix('/css/app.css', 'vendor/envalert') }}" rel="stylesheet" />--}}

@php
    use Illuminate\Support\Str;

    $envName = Str::title(Str::replace(['-', '_'], ' ', config('app.env')));
    $env = Str::lower(Str::slug(config('app.env')));
    $envColor = config("envalert.environments.{$env}.color") ?? 'blue';
    $branch = (new \DeeRig\EnvAlert\EnvAlert())->getVersion();
@endphp

<div @class([
        'envalert-py-3 envalert-px-3 envalert-text-base envalert-flex envalert-items-center',
        'envalert-border-l-6 envalert-border-blue-500 envalert-bg-blue-100 envalert-text-blue-700' => $envColor === 'blue',
        'envalert-border-l-6 envalert-border-purple-500 envalert-bg-purple-100 envalert-text-purple-700' => $envColor === 'purple',
        'envalert-border-l-6 envalert-border-green-500 envalert-bg-green-100 envalert-text-green-700' => $envColor === 'green',
        'envalert-border-l-6 envalert-border-red-500 envalert-bg-red-100 envalert-text-red-700' => $envColor === 'red',
        'envalert-border-l-6 envalert-border-yellow-500 envalert-bg-yellow-100 envalert-text-yellow-700' => $envColor === 'yellow',
        'envalert-border-l-6 envalert-border-amber-500 envalert-bg-amber-100 envalert-text-amber-700' => $envColor === 'amber',
        'envalert-border-l-6 envalert-border-orange-500 envalert-bg-orange-100 envalert-text-orange-700' => $envColor === 'orange',
        'envalert-border-l-6 envalert-border-indigo-500 envalert-bg-indigo-100 envalert-text-indigo-700' => $envColor === 'indigo',
        'envalert-border-l-6 envalert-border-gray-500 envalert-bg-gray-300 envalert-text-gray-800' => $envColor === 'gray'
    ]) role="alert">
    <x-envalert::branch class="envalert-w-5 envalert-h-5 envalert-mr-2" :color="$envColor"/> You are in the <x-envalert::badge :color="$envColor">{{ $envName }}</x-envalert::badge> environment. The current branch is <x-envalert::badge :color="$envColor">{{ $branch }}</x-envalert::badge>
</div>
