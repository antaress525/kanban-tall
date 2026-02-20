@props([
    'as' => 'div', // button, link, div
])

@php
    $baseClasses = 'px-4 flex items-center h-8 rounded font-medium text-sm text-neutral-700 hover:bg-neutral-100 transition';
@endphp

@switch($as)
    @case('button')
        <button
            type="button"
            {{ $attributes->merge(['class' => "$baseClasses cursor-pointer w-full text-left"]) }}
        >
            {{ $slot }}
        </button>
        @break
    @case('link')
        <a
            {{ $attributes->merge(['class' => "block $baseClasses"]) }}
        >
            {{ $slot }}
        </a>
        @break
    @default
        <div {{ $attributes->merge(['class' => $baseClasses]) }}>
            {{ $slot }}
        </div>
@endswitch
