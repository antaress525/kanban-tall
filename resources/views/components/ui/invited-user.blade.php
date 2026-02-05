@props(['user'])

@php
    $classes = 'group flex items-center gap-x-3 px-2.5 py-1.5 rounded-lg hover:bg-neutral-100';
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}>
    <div class="size-8 font-medium grid place-items-center rounded-lg bg-blue-100 text-blue-600">
        {{ strtoupper(substr($user->name, 0, 2)) }}
    </div>
    <div class="flex-1 flex items-center justify-between">
        <div>
            <p class="font-medium text-sm">{{ $user->name }}</p>
            <p class="text-sm text-neutral-500">
                {{ $user->email }}
            </p>
        </div>
        <button wire:click="invite('{{ $user->email }}')" class="group-hover:opacity-100 opacity-0 cursor-pointer size-8 rounded-full grid place-items-center bg-white border border-neutral-200 drop-shadow-xs">
            <x-lucide-send-horizontal class="size-4.5 text-neutral-500" />
        </button>
    </div>
</div>