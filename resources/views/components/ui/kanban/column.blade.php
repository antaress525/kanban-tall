@php
    $classes = 'min-w-full sm:min-w-[280px] md:min-w-[256px] flex flex-col gap-y-6 embla__slide'
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</div>