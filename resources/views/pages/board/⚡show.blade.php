<?php

use Livewire\Component;
use App\Models\Board;
use Livewire\Attributes\Computed;

new class extends Component
{
    public Board $board;

    public function mount(Board $board) {
        $this->board = $board->load(['tasks']);
    }

    #[Computed]
    public function tasks() {
        return $this->board->tasks()
                ->orderBy('order')->get()
                ->groupBy('status');
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
        <x-ui.input name="search" size="md" class="w-full sm:w-3xs" placeholder="Recherche">
            <x-slot:prefix>
                <x-lucide-search class="size-4 text-neutral-500"/>
            </x-slot:prefix>
        </x-ui.input>
    </div>

    <!-- Columns -->
    <div class="flex flex-1 gap-x-1 overflow-hidden">
        <!-- Todo Column -->
        <x-ui.tasks.column>
            <!-- Column header -->
            <x-ui.tasks.header class="bg-gray-800">A faire</x-tasks.header>

            <!-- Tasks -->
            <x-ui.tasks.container>
                @foreach ($this->tasks->get('todo', []) as $task)
                    <livewire:task-item :task="$task" :wire:key="$task->id" />
                @endforeach
            </x-ui.tasks.container>

            <!--Tasks empty state -->
            <x-ui.tasks.empty wire:show="tasks->get('todo')->isNotEmpty()" />

            <!-- Add task -->
            <x-ui.button variant="secondary" class="w-full">
                <x-lucide-plus class="size-4 text-neutral-500"/>
                Ajouter
            </x-ui.button>
        </x-ui.tasks.column>

        <!-- In Progress Column -->
        <x-ui.tasks.column>
            <!-- Column header -->
            <x-ui.tasks.header class="bg-amber-500">En cours</x-tasks.header>

            <!-- Tasks -->
            <x-ui.tasks.container>
                @foreach ($this->tasks->get('in_progress', []) as $task)
                    <livewire:task-item :task="$task" ::wire:key="$task->id" />
                @endforeach
            </x-ui.tasks.container>

            <!--Tasks empty state -->
            <x-ui.tasks.empty wire:show="tasks->get('in_progress')->isNotEmpty()" />

            <!-- Add task -->
            <x-ui.button variant="secondary" class="w-full">
                <x-lucide-plus class="size-4 text-neutral-500"/>
                Ajouter
            </x-ui.button>
        </x-ui.tasks.column>

        <!-- Done Column -->
        <x-ui.tasks.column>
            <!-- Column header -->
            <x-ui.tasks.header class="bg-sky-500">Revision</x-tasks.header>

            <!-- Tasks -->
            <x-ui.tasks.container>
                @foreach ($this->tasks->get('review', []) as $task)
                    <livewire:task-item :task="$task" :wire:key="$task->id" />
                @endforeach
            </x-ui.tasks.container>
            
            <!--Tasks empty state -->
            <x-ui.tasks.empty wire:show="tasks->get('review')->isNotEmpty()" />

            <!-- Add task -->
            <x-ui.button variant="secondary" class="w-full">
                <x-lucide-plus class="size-4 text-neutral-500"/>
                Ajouter
            </x-ui.button>
        </x-ui.tasks.column>
        
        <!-- Todo Column -->
        <x-ui.tasks.column>
            <!-- Column header -->
            <x-ui.tasks.header class="bg-green-500">Fait</x-tasks.header>

            <!-- Tasks -->
            <x-ui.tasks.container>
                @foreach ($this->tasks->get('done', []) as $task)
                    <livewire:task-item :task="$task" ::wire:key="$task->id" />
                @endforeach
            </x-ui.tasks.container>

            <!--Tasks empty state -->
            <x-ui.tasks.empty wire:show="tasks->get('done')->isNotEmpty()" />

            <!-- Add task -->
            <x-ui.button variant="secondary" class="w-full">
                <x-lucide-plus class="size-4 text-neutral-500"/>
                Ajouter
            </x-ui.button>
        </x-ui.tasks.column>
    </div>
</div>