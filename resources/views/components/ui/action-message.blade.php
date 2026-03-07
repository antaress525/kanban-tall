@props([
    'on',
    'autoClose' => true,
    'type' => 'success',
])

@php
    $styles = [
        'success' => 'bg-green-50 border border-green-400 text-green-400',
        'error'   => 'bg-red-50 border border-red-400 text-red-400',
        'info'    => 'bg-blue-50 border border-blue-400 text-blue-400',
        'warning' => 'bg-yellow-50 border border-yellow-400 text-yellow-400',
    ][$type] ?? 'bg-white border-gray-200 text-gray-800';

    $icons = [
        'success' => 'lucide-check-circle',
        'error'   => 'lucide-alert-circle',
        'info'    => 'lucide-info',
        'warning' => 'lucide-alert-triangle',
    ];
    $iconName = $icons[$type] ?? 'lucide-bell';
@endphp

<div
    x-data="{ 
        shown: false,
        timeout: null,
        auto: @js($autoClose)
    }"
    x-on:{{ $on }}.window="
        clearTimeout(timeout);
        shown = true;
        if (auto) {
            timeout = setTimeout(() => { shown = false }, 4000);
        }
    "
    x-show="shown"
    x-transition
    style="display: none"
    {{ $attributes->merge(['class' => 'flex gap-2.5 items-center text-sm p-3 rounded-lg w-full max-w-xs '. $styles]) }}
>
    <!-- Icon -->
    <div class="flex-shrink-0">
        <x-dynamic-component :component="$iconName" class="size-4" />
    </div>

    <!-- Content -->
    <span>
        {{ $slot }}
    </span>

    <!-- Close button -->
    <button 
        type="button" 
        @click="shown = false; clearTimeout(timeout)"
        class="text-shadow-current ml-auto cursor-pointer"
    >
        <x-lucide-x class="w-4 h-4" />
    </button>
</div>
