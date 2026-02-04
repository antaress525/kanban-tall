@php
    $class = 'bg-neutral-50 rounded-lg p-1 border border-neutral-200 space-y-2 min-h-32 overflow-y-auto'
@endphp

<div 
    {{ $attributes->merge(['class' => $class]) }}
>
    {{ $slot }}
</div>