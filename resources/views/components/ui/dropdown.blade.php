@props([
    'position' => 'bottom-left', // bottom-left, bottom-right, top-left, top-right
])

<div x-data="{ open: false }" class="relative">
    <!-- Trigger -->
    <div @click="open = !open" @click.away="open = false" class="cursor-pointer">
        {{ $trigger }}
    </div>

    <!-- Menu -->
    <div
        x-show="open"
        x-transition
        class="absolute p-1 z-50 bg-white border border-neutral-200 rounded-md shadow-lg mt-2 min-w-64"
        :class="{
            'left-0': '{{ $position }}'.includes('left'),
            'right-0': '{{ $position }}'.includes('right'),
            'bottom-full mb-2': '{{ $position }}'.includes('top'),
            'top-full mt-2': '{{ $position }}'.includes('bottom'),
        }"
    >
        {{ $menu }}
    </div>
</div>
