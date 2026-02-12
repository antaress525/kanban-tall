<?php

use Livewire\Component;
use App\Models\Task;
use Livewire\Attributes\On;

new class extends Component
{
    public Task $task;

    public function mount($task) {
        $this->task = $task;
    }

    #[On('task-updated.{task.id}')]
    #[On('member-added.{task.id}')]
    #[On('member-removed.{task.id}')]
    public function refreshItem() {
        $this->task->refresh();
    }
};
?>

<div
    {{ $attributes->merge(['class' => 'bg-white border border-neutral-200 rounded p-2 cursor-pointer task']) }}
    @click="$dispatch('open-modal', {type: 'drawer', size: 'sm', component: 'modals.task.update', props: {task_id: '{{ $task->id }}'}})"
>
    <div class="flex items-center gap-x-2">
        <h4 class="text-sm font-medium">{{ $task->title }}</h4>
    </div>
    @if ($task->description)
        <p class="text-neutral-400 text-[13px]">{{ $task->description }}</p>
    @endif
    @if ($task->due_date)
        <span class="w-max font-medium flex items-center gap-x-1 text-xs px-2 py-1 rounded-full bg-amber-500/10 text-amber-500">
            <x-lucide-calendar class="size-3.5"/>
            {{ $task->due_date->diffForHumans() }}
        </span>
    @endif

    @if ($task->assignees->count() > 0)
        <div class="py-1 mt-2 flex items-center border-t border-neutral-200">
            <x-ui.avatar-group>
                @foreach ($task->assignees()->limit(3)->get() as $member)
                    <img src="{{ $member->getAvatarUrl() }}" class="border-2 border-white rounded-lg" alt="">
                @endforeach
                @if ($task->assignees()->count() > 3)
                    <x-ui.avatar-group-count count="{{ $board->members()->count() - 3 }}" />
                @endif
            </x-ui.avatar-group>
        </div>
    @endif
</div>