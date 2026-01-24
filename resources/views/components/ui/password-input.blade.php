@props([
    'size' => 'default', // default | md | sm
])

@php
    $sizes = [
        'default' => 'h-9 text-sm',
        'md'      => 'h-8 text-sm',
        'sm'      => 'h-7 text-xs',
    ];

    $inputClasses = implode(' ', [
        'w-full',
        'bg-white',
        'border',
        'border-neutral-200',
        'rounded-md',
        'transition',
        'outline-none',

        $sizes[$size] ?? $sizes['default'],
        'pl-4',
        'pr-10',

        'focus:border-violet-500',
        'focus:ring-2',
        'focus:ring-violet-500/30',

        'disabled:bg-neutral-50',
        'disabled:cursor-not-allowed',
        'disabled:opacity-60',
    ]);
@endphp

<div x-data="{ show: false }" class="relative">
    <input
        :type="show ? 'text' : 'password'"
        aria-invalid="{{ $attributes->get('aria-invalid', 'false') }}"
        {{ $attributes->merge(['class' => $inputClasses]) }}
    >

    <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center justify-center w-10 text-neutral-500">
        <x-lucide-eye x-show="show" class="size-4.5" />
        <x-lucide-eye-off x-show="!show" class="size-4.5" />
    </button>
</div>