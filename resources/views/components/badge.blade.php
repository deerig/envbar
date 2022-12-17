@props([
    'label',
    'color' => null,
    'blue' => null,
    'purple' => null,
    'green' => null,
    'red' => null,
    'yellow' => null,
    'amber' => null,
    'orange' => null,
    'indigo' => null,
    'gray' => null
])

@php
    $color = $color ?? 'blue';
    if ($blue) $color = 'blue';
    if ($purple) $color = 'purple';
    if ($green) $color = 'green';
    if ($red) $color = 'red';
    if ($yellow) $color = 'yellow';
    if ($amber) $color = 'amber';
    if ($orange) $color = 'orange';
    if ($indigo) $color = 'indigo';
    if ($gray) $color = 'gray';
@endphp

<span @class([
    'envbar-text-sm envbar-font-medium envbar-px-2.5 envbar-py-0.5 envbar-mx-1.5 envbar-rounded-xl envbar-shadow-inner',
    'envbar-bg-blue-200 envbar-text-blue-800' => $color === 'blue',
    'envbar-bg-purple-200 envbar-text-purple-800' => $color === 'purple',
    'envbar-bg-green-200 envbar-text-green-800' => $color === 'green',
    'envbar-bg-red-200 envbar-text-red-800' => $color === 'red',
    'envbar-bg-yellow-300 envbar-text-yellow-800' => $color === 'yellow',
    'envbar-bg-amber-300 envbar-text-amber-800' => $color === 'amber',
    'envbar-bg-orange-300 envbar-text-orange-800' => $color === 'orange',
    'envbar-bg-indigo-200 envbar-text-indigo-800' => $color === 'indigo',
    'envbar-bg-gray-200 envbar-text-gray-800' => $color === 'gray',
])><b>{{ $label ?? $slot }}</b></span>
