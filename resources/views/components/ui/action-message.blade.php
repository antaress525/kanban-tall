@props([
    'on',
    'autoClose' => true,
])

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
            timeout = setTimeout(() => { shown = false }, 3000);
        }
    "
    x-show="shown"
    x-transition
    style="display: none"
    {{ $attributes->merge(['class' => 'flex items-center text-sm']) }}
>
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
