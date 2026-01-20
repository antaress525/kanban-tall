<?php

use Livewire\Component;
use App\Models\Task;

new class extends Component
{
    public Task $task;

    public function mount($task) {
        $this->task = $task;
    }
};
?>

<div class="bg-white border border-neutral-200 rounded p-2">
    <div class="flex items-center gap-x-2">
        <x-ui.checkbox />
        <h4 class="text-sm font-medium">{{ $task->title }}</h4>
    </div>
    <p class="text-neutral-400 text-[13px] mb-2">{{ $task->description }}</p>
    @if ($task->due_date)
        <span class="w-max font-medium flex items-center gap-x-1 text-xs px-2 py-1 rounded-full bg-amber-500/10 text-amber-500">
            <x-lucide-calendar class="size-3.5"/>
            {{ $task->due_date->diffForHumans() }}
        </span>
    @endif
</div>