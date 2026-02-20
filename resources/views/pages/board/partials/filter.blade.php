@props(['position' => 'bottom-left'])

<div x-data="{ open: false }" class="relative">
    <x-ui.button
        @click="open = !open"
        variant="secondary"
        size="md"
        class="font-medium relative"
    >
        <x-lucide-sliders-horizontal class="size-4 text-neutral-500"/>
            Filtre
        <span x-show="$wire.priority.length" x-transition class="absolute size-1.5 bg-red-500 rounded-full -top-1 -right-[2px]"></span>
    </x-ui.button>
    <div
        x-cloak
        x-show="open"
        @click.outside="open = false"
        x-transition
        class="absolute p-2.5 z-50 bg-white border border-neutral-200 rounded-md shadow-lg mt-2 min-w-52"
        :class="{
            'left-0': '{{ $position }}'.includes('left'),
            'right-0': '{{ $position }}'.includes('right'),
            'bottom-full mb-2': '{{ $position }}'.includes('top'),
            'top-full mt-2': '{{ $position }}'.includes('bottom'),
        }"
    >
        <div class="space-y-2">
            <h5 class="text-sm font-neutral-500">Prioriter</h5>
            <div class="flex items-center gap-2 flex-wrap">
                @foreach (App\Enum\TaskPriorityEnum::cases() as $priority)
                    <x-ui.checkbox-pill wire:model.live.debounce.500ms="priority" :value="$priority->value" :label="$priority->label()" />
                @endforeach
            </div>
        </div>
    </div>
</div>