@props(['size' => 'default'])

@php
    $baseClasses = 'place-items-center bg-white rounded-lg border border-neutral-200 shadow-2xs grid cursor-pointer disabled:bg-neutral-500';

    $sizeClasses = [
        'default' => 'size-9',
        'md' => 'size-8',
        'sm' => 'size-7',
    ][$size] ?? 'size-9';

    // $focusClasses = 'focus-visible:ring-1 focus-visible:ring-indigo-500';

    $classes = "$baseClasses $sizeClasses";
@endphp

<button {{ $attributes->merge(["class" => $classes]) }}>
    {{ $slot }}
</button>