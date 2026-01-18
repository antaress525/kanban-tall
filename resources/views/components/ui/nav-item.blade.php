@props([
    'active' => false, // Boolean to indicate if the item is active
    'disabled' => false, // Boolean to indicate if the item is disabled
])

@php
    $baseClasses = 'flex items-center gap-2 w-full px-2.5 h-8 rounded-md text-sm font-medium';
    $stateClasses = $disabled
        ? 'opacity-50 cursor-not-allowed'
        : ($active
            ? 'bg-indigo-500/15 text-indigo-500 relative'
            : 'text-neutral-400 hover:bg-indigo-500/15 hover:text-indigo-500');
    $focusClasses = 'focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-violet-500';
    $classes = "$baseClasses $stateClasses $focusClasses";
@endphp

<a
    {{ $active ? 'aria-current=page' : '' }}
    {{ $attributes->merge(['class' => $classes]) }}
>
    @if ($active)
        <span class="absolute -left-3.5 h-full w-[3px] bg-indigo-500 rounded-r-lg"></span>
    @endif
    @if (isset($icon))
        <span class="flex-shrink-0">
            {{ $icon }}
        </span>
    @endif
    <span class="truncate">
        {{ $slot }}
    </span>
</a>