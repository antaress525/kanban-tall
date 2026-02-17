@props(['label'])

@php
    $classes = 'mb-1.5 text-xs font-medium px-2.5 py-0.5  rounded w-max'
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}>
    {{ $label }}
</div>