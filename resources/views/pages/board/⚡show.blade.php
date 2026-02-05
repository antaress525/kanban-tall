<?php

use Livewire\Component;
use App\Models\Board;
use Livewire\Attributes\Url;
use App\Models\Task;
use App\Enum\TaskStatuEnum;

new class extends Component
{
    public Board $board;
    public array $selected = [];

    public array $columns = [];

    #[Url(except: '')]
    public string $search = '';

    public function mount(Board $board) {
        $this->board = $board->load(['tasks']);

        $this->columns = [
            TaskStatuEnum::TODO->value => TaskStatuEnum::TODO->label(),
            TaskStatuEnum::INPROGRESS->value => TaskStatuEnum::INPROGRESS->label(),
            TaskStatuEnum::REVIEW->value => TaskStatuEnum::REVIEW->label(),
            TaskStatuEnum::DONE->value => TaskStatuEnum::DONE->label()
        ];
    }

    public function deleteSelected() {
        Task::destroy($this->selected);
        $this->reset('selected');
        $this->dispatch('notify', type: 'success', message: 'Les taches on bien ete supprimer');
    }

    public function render()
    {
        return $this->view()
            ->title($this->board->name); 
    }
};
?>

<div class="p-3.5 flex flex-col h-full">
    <!-- Board Header -->   
    <div class="flex items-center justify-between mb-4">
        <div class="flex items-center gap-x-3">
            <div 
                class="size-8 font-medium grid place-items-center rounded-lg" style="background-color: {{ $board->color.'26' }}; color: {{ $board->color }}; background-opacity: 0.1;"
            >
                {{ strtoupper(substr($board->name, 0, 1)) }}
            </div>
            <!-- Board name desktop -->
            <h3 class="text-lg hidden sm:block sm:text-xl truncate font-medium">{{ $board->name }}</h3>

            <!-- Board name mobile -->
            <h3 class="text-lg sm:hidden sm:text-xl truncate font-medium">{{ Str::limit(Str::ucfirst($board->name), 9) }}</h3>
        </div>
        <div class="flex items-center gap-x-2">
            @can('invite', $board)
                <x-ui.button 
                    @click="$dispatch('open-modal', {type: 'center', component: 'modals.invite-user', size: 'xl', props: {board_id: '{{ $board->id }}' } })"
                    variant="secondary" 
                    size="md" 
                    class="font-medium"
                >
                    <x-lucide-user-plus class="size-4 text-neutral-500"/>
                    Inviter
                </x-ui.button>
            @endcan
            
            <x-ui.button variant="secondary" size="md" class="font-medium">
                <x-lucide-settings class="size-4 text-neutral-500"/>
                Parametre
            </x-ui.button>
        </div>
    </div>

    <!-- Board action -->
    <div class="flex items-center gap-x-2 justify-end mb-6">
        <x-ui.button 
            variant="secondary" 
            size="md" 
            class="font-medium"
        >
            <x-lucide-sliders-horizontal class="size-4 text-neutral-500"/>
            Filtre
        </x-ui.button>
        <x-ui.input name="search" wire:model.live.debounce.500ms="search" size="md" class="w-full sm:w-3xs" placeholder="Recherche">
            <x-slot:prefix>
                <x-ui.spinner wire:loading wire:target="search" class="size-4" />
                <x-lucide-search wire:loading.remove wire:target="search" class="size-4 text-neutral-500"/>
            </x-slot:prefix>
        </x-ui.input>
    </div>

    <!-- Columns -->
    <div class="flex flex-1 gap-x-2 overflow-hidden">
        @foreach ($columns as $status => $title)
            <livewire:kanban.column :title="$title" :status="$status" :board="$board" />
        @endforeach 
    </div>

    <!-- Delete selected -->
    <div 
        x-show="$wire.selected.length > 0"
        x-transition
        x-cloak
        class="fixed bottom-3.5 left-0 right-0 flex items-center justify-center gap-x-3.5"
    >
        <!-- Count -->
        <span class="size-8 rounded-full text-white text-sm font-medium bg-black grid place-items-center" x-text="$wire.selected.length">
        </span>

        <!-- Action -->
        <div class="p-1 rounded-full shadow-lg h-9 bg-black text-white w-28">
            <button wire:click="deleteSelected" class="size-full px-0.5 flex items-center justify-center gap-x-1 text-[13px] font-medium rounded-full bg-red-500/15 text-red-500">
                <x-lucide-trash-2 class="size-4 in-data-loading:hidden" />
                <x-ui.spinner class="size-4 fill-red-500 not-in-data-loading:hidden" />
                Supprimer
            </button>
        </div>

        <!-- Close -->
        <div class="size-8 rounded-full bg-black p-1 grid place-items-center">
            <button @click="$wire.selected = []" class="hover:bg-white/10 size-full rounded-full grid place-items-center cursor-pointer">
                <x-lucide-x class="size-3.5 text-white" />
            </button>
        </div>
    </div>
</div>