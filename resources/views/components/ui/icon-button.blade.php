@php
    $classes = "place-items-center bg-white size-7 rounded-lg border border-neutral-200 shadow grid cursor-pointer disabled:bg-neutral-500";
@endphp

<button {{ $attributes->merge(["class" => $classes]) }} wire:click="delete" title="Supprimer">
    {{ $slot }}
</button>