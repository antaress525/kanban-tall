@props([
    'value' => null,
    'placeholder' => 'Choisir une date',
    'disablePast' => false,
])

<div
    x-data="datePicker({
        value: '{{ $value }}',
        disablePast: @js($disablePast),
     })"
    x-modelable="modelValue"
    {{ $attributes }}
    x-init="init()"
    {{ $attributes->merge(['class' => 'relative w-46']) }}
>
    <button
        type="text"
        @click="open = !open"
        readonly
        placeholder="{{ $placeholder }}"
        class="group w-full border h-8 flex items-center justify-start shadow-xs border-neutral-200 rounded-md px-3 py-2 text-sm bg-white cursor-pointer"
    >
        <x-lucide-calendar class="size-4 text-neutral-500 mr-2"/>
        <span x-text="formatted || '{{ $placeholder }}'"></span>
        <x-lucide-chevron-down class="size-4 text-neutral-500 group-hover:text-black ml-auto"/>
    </button>

    <div
        x-show="open"
        @click.away="open = false"
        x-transition
        x-cloak
        class="absolute mt-2 bg-white border border-neutral-200 rounded-xl shadow-lg p-4 w-72 z-50"
    >
        <!-- header -->
        <div class="flex justify-between items-center mb-4">
            <x-ui.icon-button size="sm" type="button" @click="prevMonth()">
                <x-lucide-chevron-left class="size-4 text-neutral-500"/>
            </x-ui.icon-button>
            <div class="text-sm font-medium" x-text="monthLabel"></div>
            <x-ui.icon-button size="sm" type="button" @click="nextMonth()">
                <x-lucide-chevron-right class="size-4 text-neutral-500"/>
            </x-ui.icon-button>
        </div>

        <!-- days -->
        <div class="grid grid-cols-7 gap-1 text-sm text-center">
            <template x-for="day in calendar" :key="day.date">
                <button
                    type="button"
                    @click="select(day)"
                    class="h-9 rounded-md"
                    :disabled="day.isDisabled"
                    :class="{
                        'text-neutral-300': !day.currentMonth,
                        'opacity-40 cursor-not-allowed': day.isDisabled,
                        'hover:bg-neutral-50': !day.isDisabled,
                        'bg-indigo-400 text-white': isSelected(day)
                    }"
                    x-text="day.day"
                ></button>
            </template>
        </div>
    </div>
</div>