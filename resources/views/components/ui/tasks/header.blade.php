<div {{ $attributes->merge(['class' => 'h-9 p-2 text-white font-medium flex items-center justify-between rounded-lg']) }}>
    {{ $slot }}
    <button class="cursor-pointer"><x-lucide-plus class="size-4 text-white"/></button>
</div>