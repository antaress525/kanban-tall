<?php

use Livewire\Component;
use App\Livewire\Forms\Task\CreateForm;
use App\Attributes\Locked;
use App\Models\Board;
use App\Enum\TaskStatuEnum;

new class extends Component
{
    public CreateForm $form;
    public Board $board;

    #[Locked]
    public string $status = 'to_do';


    public function mount(array $props) {
        $this->board = Board::findOrFail($props['board_id']);
        $this->setStatus($props['status']);
    }

    public function setStatus(string $status) {
        if(in_array($status, ['to_do', 'in_progress', 'review', 'done'])) {
            $this->status = $status;
        }
        else {
            $this->reset('status');
        }
    }

    public function save() {
        $attributes = $this->validate();
        $attributes = [
            ...$attributes,
            'status' => $this->status
        ];
        $this->board->tasks()->create($attributes);
        $this->dispatch('close-modal');
        $this->dispatch('task-created');
        $this->dispatch('notify', type: 'success', message: 'La tache a bien ete creer');

    }

};
?>

<form wire:submit="save">
    <div class="flex items-center justify-between p-3.5">
        <div class="flex items-center gap-x-3.5">
            <h2 class="text-base font-medium">Nouvelle tache</h2>
            @switch($status)
                @case('in_progress')
                    <span class="px-2 py-1 text-xs font-medium {{ TaskStatuEnum::INPROGRESS->color() }} rounded-full">{{ TaskStatuEnum::INPROGRESS->label() }}</span>
                @break

                @case('review')
                    <span class="px-2 py-1 text-xs font-medium {{ TaskStatuEnum::REVIEW->color() }} rounded-full">{{ TaskStatuEnum::REVIEW->label() }}</span>
                @break

                @case('done')
                    <span class="px-2 py-1 text-xs font-medium {{ TaskStatuEnum::DONE->color() }} rounded-full">{{ TaskStatuEnum::DONE->label() }}</span>
                @break

                @default
                    <span class="px-2 py-1 text-xs font-medium {{ TaskStatuEnum::TODO->color() }} rounded-full">{{ TaskStatuEnum::TODO->label() }}</span>    
            @endswitch

        </div>
        <x-ui.close-button type="button" 
            @click="$dispatch('close-modal')" 
        />
    </div>
    <div class="p-3.5 space-y-3.5">
        <x-ui.field>
            <x-ui.input name="new-task" wire:model="form.title" size="md" placeholder="Nouvelle tache" autofocus />
            @error('form.title')
                <x-ui.error>{{ $message }}</x-ui.error>
            @enderror
        </x-ui.field>
        <div class="flex items-center justify-end gap-x-2">
            <x-ui.button size="md" 
                variant="secondary"
                type="button"
                @click="$dispatch('close-modal')"
            >
                Annuler
            </x-ui.button>
            <x-ui.button size="md">
                <span class="not-in-data-loading:hidden">Creer la tache...</span>
                <span class="in-data-loading:hidden">Creer la tache</span>
            </x-ui.button>
        </div>
    </div>
</form>