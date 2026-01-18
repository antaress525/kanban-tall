@props(['avatar' => null, 'name' => null])

<div {{ $attributes->merge(['class' => "flex items-center justify-between gap-3 px-3 py-2 rounded-lg bg-neutral-900 text-sm font-medium text-white transition-colors hover:bg-neutral-800"]) }}>
    <div class="flex items-center gap-x-2">
        <!-- Avatar -->
        <div class="w-8 h-8 rounded-full bg-gray-700 flex items-center justify-center overflow-hidden" aria-label="{{ $name }}">
            @if ($avatar)
                <img src="{{ $avatar }}" alt="{{ $name }}" class="w-full h-full object-cover">
            @else
                <span class="text-xs font-bold text-white">{{ strtoupper(substr($name, 0, 1)) }}</span>
            @endif
        </div>

        <!-- User Name -->
        <span class="truncate">{{ $name }}</span>
    </div>
    <x-lucide-log-out class="text-red-500 size-4" />
</div>