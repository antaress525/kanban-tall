<?php

use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('home')] class extends Component
{
    //
};
?>

<div class="w-screen h-screen p-6 space-y-4">
    <h1 class="text-3xl font-bold underline">
        Hello, Kanban!
    </h1>
    <x-ui.button variant="secondary" size="md" class="font-bold">
        Mettre à jour
    </x-ui.button>
    
    <x-ui.link href="#">
        Learn more
    </x-ui.link>

    <x-ui.checkbox />
    <x-ui.input class="w-sm" size="md" placeholder="Input field" name="input-field" >
        <x-slot:prefix>
            <x-lucide-search class="size-4 text-neutral-400" />
        </x-slot:prefix>
    </x-ui.input>
</div>