<?php

use Livewire\Component;
use App\Models\Board;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Async;
use Livewire\Attributes\Renderless;

new class extends Component
{
    public string $title = '';
    public string $status = '';
    public Board $board;

    #[Computed]
    public function tasks() {
        return $this->board->tasks()->where('status', $this->status)
            ->orderBy('order', 'asc')
            ->get();
    }

    #[On('task-created')]
    public function refreshColumn() {
        $this->board->refresh();
    }

    #[Renderless, Async]
    public function moveTask($item, $position) {
        $task = $this->board->tasks()->findOrFail($item);
        $task->reorder($this->status, $position);
    }
};
?>

<div class ="flex-1 flex flex-col gap-y-6">
    <!-- Heading -->
    <x-ui.kanban.header 
        :board_id="$board->id" 
        :status="$status" 
        class="bg-zinc-50 shadow-xs border border-neutral-200 text-black"
        :title="$title"
    />

    <!-- Tasks -->
    <x-ui.kanban.container wire:sort="moveTask"  wire:sort:group="columns">
        @foreach ($this->tasks as $task)
            <livewire:kanban.item :task="$task" :wire:key="$task->id" wire:sort:item="{{ $task->id }}">
                <livewire:slot name="checkbox">
                    <x-ui.checkbox :value="$task->id" /> 
                </livewire:slot>
            </livewire:kanban.item>
        @endforeach
    </x-ui.kanban.container>

    <!-- Add task -->
    <x-ui.button 
        variant="secondary" 
        class="w-full"
        @click="$dispatch('open-modal', {type: 'center', component: 'modals.task.create', size: 'sm', props: {board_id: '{{ $board->id }}', status: '{{ $status }}'}})"
    >
        <x-lucide-plus class="size-4 text-neutral-500"/>
        Ajouter
    </x-ui.button>
</div>