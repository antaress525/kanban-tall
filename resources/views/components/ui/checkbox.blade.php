<label class="relative inline-flex items-center justify-center cursor-pointer">
    <input
        type="checkbox"
        {{ $attributes->merge(['class' => 'peer sr-only']) }}
    >
    <x-lucide-check class="size-3.5 absolute text-white hidden peer-checked:block" />
    <span class="grid place-items-center size-4 rounded border border-neutral-300
            peer-checked:bg-indigo-500
            peer-checked:border-transparent
            peer-focus-visible:ring-2
            peer-focus-visible:ring-indigo-400
            peer-disabled:bg-neutral-50
            transition">
    </span>
</label>