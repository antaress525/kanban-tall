<?php

use Livewire\Component;
use App\Livewire\Forms\BoardForm;
use App\Models\Board;

new class extends Component
{
    public BoardForm $form;
    public Board $board;


    public function mount($props) {
        $this->board = Board::findOrFail($props['board_id']);
        $this->authorize('update', $this->board);
        $this->form->setBoard($this->board);
    }

    public function update() {
        $attributes = $this->validate();
        $this->board->update($attributes);
        $this->dispatch('close-modal');
        $this->dispatch("board-updated.{$this->board->id}");
        $this->dispatch('notify', type: 'success', message: 'Le projet a été mis à jour.');
    }
};
?>

<form wire:submit="update" class="rounded-lg p-2 flex items-start gap-x-2">
    <x-ui.field>
        <x-ui.input name="create-board" wire:model="form.name" size="md" placeholder="Creation d'un tableau" autofocus />
        @error('form.name')
            <x-ui.error>{{ $message }}</x-ui.error>
        @enderror
    </x-ui.field>
    <x-ui.button size="md">
        <x-lucide-plus class="in-data-loading:hidden size-4" />
        <x-ui.spinner class="not-in-data-loading:hidden size-4 fill-white" />
        Modifier
    </x-ui.button>
</form >