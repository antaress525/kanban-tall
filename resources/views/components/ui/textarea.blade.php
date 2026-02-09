@php
    $classes = 'p-3 bg-white text-sm border border-neutral-200 rounded-lg placeholder:text-sm placeholder:text-neutral-500 focus:ring-2 focus:ring-violet-500/30 focus:border-violet-500 outline-none transition disabled:bg-neutral-50 disabled:cursor-not-allowed disabled:opacity-60';
@endphp

<textarea {{ $attributes->merge(['class' => $classes]) }}></textarea>