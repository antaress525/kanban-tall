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
    public string $search = '';

    #[Computed]
    public function tasks() {
        return $this->board->tasks()->where('status', $this->status)
            ->when($this->search, function($query) {
                $query->whereLike('title', '%'.$this->search.'%');
            })
            ->orderBy('order', 'asc')
            ->get();
    }

    #[On('search-task')]
    public function searchTask(string $search) {
        $this->search = trim($search);
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

<x-ui.kanban.column class="{{ $status }}">
    <!-- Heading -->
    <x-ui.kanban.header
        :board_id="$board->id"
        :status="$status"
        class="bg-zinc-50 shadow-xs border border-neutral-200 text-black"
        :title="$title"
        :count="$this->tasks->count()"
    >
        <x-slot:icon>
            @switch($status)
                @case('to_do')
                    <x-lucide-circle-dashed class="size-4 text-black" />
                    @break
                @case('in_progress')
                    <x-lucide-circle-dashed class="size-4 text-orange-400" />
                    @break
                @case('review')
                    <x-lucide-circle-dashed class="size-4 text-blue-400" />
                    @break
                @default
                    <x-lucide-circle-check class="size-4.5 text-green-400" />
            @endswitch
        </x-slot:icon>
    </x-ui.kanban.header>

    <!-- Tasks -->
    <x-ui.kanban.container
        wire:sort="moveTask"
        wire:sort:group="columns"
    >
        @foreach ($this->tasks as $task)
            <livewire:kanban.item
                :task="$task"
                :wire:key="$task->id"
                wire:sort:item="{{ $task->id }}"
            />
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
</x-ui.kanban.column>

<script>
    const status = [
        'to_do',
        'in_progress',
        'review',
        'done'
    ]
    const updateShowCount = (el) => {
        const showCount = el.querySelector('.task__count')
        let count = Array.from(el.querySelectorAll('.task')).length
        if(!count) {
            count = ''
        }
        showCount.innerText = count
    }
    this.intercept('moveTask', ({ onSuccess }) => {
        onSuccess(() => {
            status.forEach((statu) => {
                updateShowCount(document.querySelector(`.${statu}`))
            })
        })
    })
</script>