@props([
    'label',
    'description' => null,
])

<label class="cursor-pointer block relative">
    <input
        type="radio"
        {{ $attributes }}
        class="sr-only peer"
    >
    <!-- Circle -->
    <div class="absolute top-[15px] left-[17px] size-4.5 shadow-2xs rounded-full border flex items-center justify-center
                        border-neutral-200
                        after:rounded-full peer-checked:after:bg-white after:size-2
                        peer-checked:bg-indigo-500 peer-checked:border-indigo-500">
    </div>

    <div
        class="rounded-lg border shadow-xs p-3 duration-200
               border-neutral-200 bg-white
               peer-checked:border-indigo-500
               hover:bg-neutral-50"
    >
        <div class="ml-8 flex flex-col items-start gap-1 mb-1">
            <span class="font-medium text-sm">
                {{ $label }}
            </span>
             @if($description)
                <p class="text-xs text-neutral-400">
                    {{ $description }}
                </p>
            @endif
        </div>

    </div>
</label>
