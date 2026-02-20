@props([
    'label',
])

<label class="inline-block cursor-pointer overflow-hidden">
    <input
        type="checkbox"
        {{ $attributes->merge(['class' => 'sr-only peer']) }}
    >

    <div
        class="px-3 h-8 flex items-center rounded-full text-sm border transition-all duration-200
               border-neutral-200 bg-white font-medium shadow-xs
               peer-checked:bg-indigo-400
               peer-checked:text-white
               peer-checked:border-transparent"
    >
        {{ $label }}
    </div>
</label>
