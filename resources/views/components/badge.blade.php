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
    'envalert-text-sm envalert-font-medium envalert-px-2.5 envalert-py-0.5 envalert-mx-1.5 envalert-rounded-xl envalert-shadow-inner',
    'envalert-bg-blue-200 envalert-text-blue-800' => $color === 'blue',
    'envalert-bg-purple-200 envalert-text-purple-800' => $color === 'purple',
    'envalert-bg-green-200 envalert-text-green-800' => $color === 'green',
    'envalert-bg-red-200 envalert-text-red-800' => $color === 'red',
    'envalert-bg-yellow-300 envalert-text-yellow-800' => $color === 'yellow',
    'envalert-bg-amber-300 envalert-text-amber-800' => $color === 'amber',
    'envalert-bg-orange-300 envalert-text-orange-800' => $color === 'orange',
    'envalert-bg-indigo-200 envalert-text-indigo-800' => $color === 'indigo',
    'envalert-bg-gray-200 envalert-text-gray-800' => $color === 'gray',
])><b>{{ $label ?? $slot }}</b></span>
