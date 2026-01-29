<?php

use Livewire\Component;
use App\Models\Board;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Task;

new class extends Component
{
    public Board $board;
    public array $selected = [];

    #[Url(except: '')]
    public string $search = '';

    protected $listeners = [
        'task-created' => '$refresh'
    ];

    public function mount(Board $board) {
        $this->board = $board->load(['tasks']);
    }

    #[Computed]
    public function tasks() {
        return $this->board->tasks()
            ->when($this->search, fn(Builder $query) => $query->whereLike('title', "%$this->search%"))
            ->orderBy('order')->get()
            ->groupBy('status');
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
            <x-ui.button variant="secondary" size="md" class="font-medium">
                <x-lucide-user-plus class="size-4 text-neutral-500"/>
                Inviter
            </x-ui.button>
            <x-ui.button variant="secondary" size="md" class="font-medium">
                <x-lucide-settings class="size-4 text-neutral-500"/>
                Parametre
            </x-ui.button>
        </div>
    </div>

    <!-- Board action -->
    <div class="flex items-center gap-x-2 justify-end mb-6">
        <x-ui.button variant="secondary" size="md" class="font-medium">
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
    <div class="flex flex-1 gap-x-1 overflow-hidden">
        <!-- to_do Column -->
        <x-ui.tasks.column>
            <!-- Column header -->
            <x-ui.tasks.header 
                :board_id="$board->id" 
                status="to_do" class="bg-gray-800"
            >
                A faire
            </x-tasks.header>

            <!-- Tasks -->
            <x-ui.tasks.container>
                @foreach ($this->tasks->get('to_do', []) as $task)
                    <livewire:task-item :task="$task" :wire:key="$task->id">
                        <livewire:slot name="checkbox">
                            <x-ui.checkbox :value="$task->id" wire:model="selected" /> 
                        </livewire:slot>
                    </livewire:task-item>
                @endforeach
            </x-ui.tasks.container>

            <!--Tasks empty state -->
            <x-ui.tasks.empty wire:show="tasks->get('to_do')->isNotEmpty()" />

            <!-- Add task -->
            <x-ui.button 
                variant="secondary" 
                class="w-full"
                @click="$dispatch('open-modal', {type: 'center', component: 'modals.task.create', size: 'sm', props: {board_id: '{{ $board->id }}', status: 'to_do'}})"
            >
                <x-lucide-plus class="size-4 text-neutral-500"/>
                Ajouter
            </x-ui.button>
        </x-ui.tasks.column>

        <!-- In Progress Column -->
        <x-ui.tasks.column>
            <!-- Column header -->
            <x-ui.tasks.header :board_id="$board->id" status="in_progress" class="bg-amber-500">En cours</x-tasks.header>

            <!-- Tasks -->
            <x-ui.tasks.container>
                @foreach ($this->tasks->get('in_progress', []) as $task)
                    <livewire:task-item :task="$task" :wire:key="$task->id">
                        <livewire:slot name="checkbox">
                            <x-ui.checkbox :value="$task->id" wire:model="selected" /> 
                        </livewire:slot>
                    </livewire:task-item>
                @endforeach
            </x-ui.tasks.container>

            <!--Tasks empty state -->
            <x-ui.tasks.empty wire:show="tasks->get('in_progress')->isNotEmpty()" />

            <!-- Add task -->
            <x-ui.button 
                variant="secondary" 
                class="w-full"

                @click="$dispatch('open-modal', {type: 'center', component: 'modals.task.create', size: 'sm', props: {board_id: '{{ $board->id }}', status: 'in_progress'}})"
            >
                <x-lucide-plus class="size-4 text-neutral-500"/>
                Ajouter
            </x-ui.button>
        </x-ui.tasks.column>

        <!-- Done Column -->
        <x-ui.tasks.column>
            <!-- Column header -->
            <x-ui.tasks.header :board_id="$board->id" status="review" class="bg-sky-500">Revision</x-tasks.header>

            <!-- Tasks -->
            <x-ui.tasks.container>
                @foreach ($this->tasks->get('review', []) as $task)
                     <livewire:task-item :task="$task" :wire:key="$task->id">
                        <livewire:slot name="checkbox">
                            <x-ui.checkbox :value="$task->id" wire:model="selected" /> 
                        </livewire:slot>
                    </livewire:task-item>
                @endforeach
            </x-ui.tasks.container>
            
            <!--Tasks empty state -->
            <x-ui.tasks.empty wire:show="tasks->get('review')->isNotEmpty()" />

            <!-- Add task -->
            <x-ui.button 
                variant="secondary" 
                class="w-full"

                @click="$dispatch('open-modal', {type: 'center', component: 'modals.task.create', size: 'sm', props: {board_id: '{{ $board->id }}', status: 'review'}})"
            >
                <x-lucide-plus class="size-4 text-neutral-500"/>
                Ajouter
            </x-ui.button>
        </x-ui.tasks.column>
        
        <!-- to_do Column -->
        <x-ui.tasks.column>
            <!-- Column header -->
            <x-ui.tasks.header :board_id="$board->id" status="done" class="bg-green-500">Fait</x-tasks.header>

            <!-- Tasks -->
            <x-ui.tasks.container>
                @foreach ($this->tasks->get('done', []) as $task)
                     <livewire:task-item :task="$task" :wire:key="$task->id">
                        <livewire:slot name="checkbox">
                            <x-ui.checkbox :value="$task->id" wire:model="selected" /> 
                        </livewire:slot>
                    </livewire:task-item>
                @endforeach
            </x-ui.tasks.container>

            <!--Tasks empty state -->
            <x-ui.tasks.empty wire:show="tasks->get('done')->isNotEmpty()" />

            <!-- Add task -->
            <x-ui.button 
                variant="secondary" 
                class="w-full"

                @click="$dispatch('open-modal', {type: 'center', component: 'modals.task.create', size: 'sm', props: {board_id: '{{ $board->id }}', status: 'done'}})"
            >
                <x-lucide-plus class="size-4 text-neutral-500"/>
                Ajouter
            </x-ui.button>
        </x-ui.tasks.column>
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