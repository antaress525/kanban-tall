<?php

use Livewire\Component;
use App\Livewire\Forms\BoardForm;

new class extends Component
{
    public BoardForm $form;

    public function save() {
        $attributes = $this->validate();
        auth()->user()->boards()->create($attributes);
        $this->dispatch('close-modal');
        $this->dispatch('board-created');
        $this->dispatch('notify', type: 'success', message: 'Tableau créé avec succès !');
    }
};
?>


<form wire:submit="save" class="rounded-lg p-2 flex items-start gap-x-2">
    <x-ui.field>
        <x-ui.input name="create-board" wire:model="form.name" size="md" placeholder="Creation d'un tableau" autofocus />
        @error('form.name')
            <x-ui.error>{{ $message }}</x-ui.error>
        @enderror
    </x-ui.field>
    <x-ui.button size="md">
        <x-lucide-plus class="in-data-loading:hidden size-4" />
        <x-ui.spinner class="not-in-data-loading:hidden size-4 fill-white" />
        Creer
    </x-ui.button>
</form >
