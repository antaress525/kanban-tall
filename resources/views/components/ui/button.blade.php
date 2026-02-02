@props([
    'variant' => 'primary', // primary, secondary, danger
    'size' => 'default', // default, md
])

@php
    $baseClasses = 'inline-flex items-center text-sm justify-center gap-2 rounded-md transition-colors focus-visible:outline-none disabled:opacity-60 disabled:cursor-not-allowed disabled:hover:bg-none';

    $sizeClasses = [
        'default' => 'h-9 px-4',
        'md' => 'h-8 px-3',
    ][$size] ?? 'h-9 px-4';

    $variantClasses = [
        'primary' => 'bg-indigo-500 text-white hover:bg-indigo-600 font-semibold',
        'secondary' => 'bg-white text-neutral-800 border border-neutral-200 shadow-2xs hover:bg-neutral-50 font-medium',
        'danger' => 'bg-red-500 text-white hover:bg-red-600 font-medium',
    ][$variant] ?? 'bg-indigo-500 text-white hover:bg-indigo-600';

    $focusClasses = 'focus-visible:ring-1 focus-visible:ring-indigo-500';

    $classes = "$baseClasses $sizeClasses $variantClasses $focusClasses";
@endphp

<button
    {{ $attributes->merge(['class' => $classes]) }}
>
    {{ $slot }}
</button>