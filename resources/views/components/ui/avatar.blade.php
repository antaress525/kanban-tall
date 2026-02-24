@props(['avatar' => null, 'name' => 'Unkown', 'rounded' => 'full'])

@php
    $classes = "size-8 rounded-{$rounded} flex items-center justify-center overflow-hidden";
@endphp

<div {{ $attributes->merge(['class' => $classes]) }} aria-label="{{ $name }}">
    <img src="{{ $avatar }}" alt="{{ $name }}" class="w-full h-full object-cover">
</div>