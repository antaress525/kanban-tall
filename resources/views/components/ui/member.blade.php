@props(['user'])

@php
    $classes = 'flex items-center gap-x-3 px-2.5 py-1.5 hover:bg-neutral-100 cursor-pointer'
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}>
    <x-ui.avatar :avatar="$user->getAvatarUrl()"  rounded="lg" />
    <div class="flex-1 flex items-center justify-between">
        <div>
            <p class="font-medium text-sm">{{ $user->name }}</p>
            <p class="text-sm text-neutral-500">
                {{ $user->email }}
            </p>
        </div>
        <button wire:click="invite('{{ $user->email }}')" class="group-hover:opacity-100 opacity-0 cursor-pointer size-8 rounded-full grid place-items-center bg-white border border-neutral-200 drop-shadow-xs">
            <x-ui.spinner class="not-in-data-loading:hidden size-4.5 fill-neutral-400" />
            <x-lucide-send-horizontal class="in-data-loading:hidden size-4 text-neutral-500" />
        </button>
    </div>
</div>