@props([
    'type' => 'text',
    'name',
    'value' => null,
    'placeholder' => null,
    'size' => 'default', // default | md | sm
    'disabled' => false,
    'required' => false,
])

@php
    /*
    |--------------------------------------------------------------------------
    | Sizes
    |--------------------------------------------------------------------------
    */
    $sizes = [
        'default' => 'h-9 text-sm',
        'md'      => 'h-8 text-sm',
        'sm'      => 'h-7 text-xs',
    ];

    /*
    |--------------------------------------------------------------------------
    | Slots detection
    |--------------------------------------------------------------------------
    */
    $hasPrefix = isset($prefix);
    $hasSuffix = isset($suffix);

    /*
    |--------------------------------------------------------------------------
    | Dynamic paddings
    |--------------------------------------------------------------------------
    | - 14px (~pl-4 / pr-4) when no icon
    | - Icon width = w-10 (40px)
    */
    $paddingLeft  = $hasPrefix ? 'pl-10' : 'pl-4';
    $paddingRight = $hasSuffix ? 'pr-10' : 'pr-4';

    /*
    |--------------------------------------------------------------------------
    | Input classes
    |--------------------------------------------------------------------------
    */
    $inputClasses = implode(' ', [
        'w-full',
        'bg-white',
        'border',
        'border-neutral-200',
        'rounded-md',
        'transition',
        'outline-none',

        $sizes[$size] ?? $sizes['default'],
        $paddingLeft,
        $paddingRight,

        // Focus (léger : 1px)
        'focus:border-violet-500',
        'focus:ring-1',
        'focus:ring-violet-500/30',

        // Disabled
        'disabled:bg-neutral-50',
        'disabled:cursor-not-allowed',
        'disabled:opacity-60',
    ]);
@endphp

<div {{ $attributes->merge(['class' => 'relative']) }}>
    {{-- Prefix --}}
    @isset($prefix)
        <span class="absolute inset-y-0 left-0 flex items-center justify-center w-10 text-neutral-500 pointer-events-none">
            {{ $prefix }}
        </span>
    @endisset

    {{-- Input --}}
    <input
        type="{{ $type }}"
        name="{{ $name }}"
        value="{{ old($name, $value) }}"
        placeholder="{{ $placeholder }}"
        id="{{ $attributes->get('id', $name) }}"
        aria-invalid="{{ $attributes->get('aria-invalid', 'false') }}"
        {{ $disabled ? 'disabled' : '' }}
        {{ $required ? 'required' : '' }}
        class="{{ $inputClasses }}"
    >

    {{-- Suffix --}}
    @isset($suffix)
        <span class="absolute inset-y-0 right-0 flex items-center justify-center w-10 text-neutral-500 pointer-events-none">
            {{ $suffix }}
        </span>
    @endisset
</div>