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
    #[On('priority-updated.{task.id}')]
    #[On('member-added.{task.id}')]
    #[On('member-removed.{task.id}')]
    #[On('due-date-updated.{task.id}')]
    public function refreshItem() {
        $this->task->refresh();
    }
};
?>

<div
    {{ $attributes->merge(['class' => 'bg-white border border-neutral-200 rounded p-2 cursor-pointer task']) }}
    @click="$dispatch('open-modal', {type: 'drawer', size: 'sm', component: 'modals.task.update', props: {task_id: '{{ $task->id }}'}})"
>
    @if ($task->priority->value == 'low')
        <x-ui.task-tag :label="$task->priority->label()" class="{{ $task->priority->classes() }}" />
    @endif
    @if ($task->priority->value == 'medium')
        <x-ui.task-tag :label="$task->priority->label()" class="{{ $task->priority->classes() }}" />
    @endif
    @if ($task->priority->value == 'high')
       <x-ui.task-tag :label="$task->priority->label()" class="{{ $task->priority->classes() }}" />
    @endif

    <div class="flex items-center gap-x-2">
        <h4 class="text-sm font-medium">{{ $task->title }}</h4>
    </div>

    @if ($task->description)
        <p class="text-neutral-400 text-[13px]">{{ $task->description }}</p>
    @endif

    @if ($task->assignees->count() > 0)
        <div class="py-1 mt-2 flex items-center border-t border-neutral-200">
            <x-ui.avatar-group>
                @foreach ($task->assignees()->limit(3)->get() as $member)
                    <img src="{{ $member->getAvatarUrl(28) }}" class="border-2 border-white rounded-lg" alt="">
                @endforeach
                @if ($task->assignees()->count() > 3)
                    <x-ui.avatar-group-count count="{{ $task->assignees()->count() - 3 }}" />
                @endif
            </x-ui.avatar-group>
            @if ($task->due_date)
                <div class="ml-auto px-2.5 py-1.5 rounded-full text-xs text-neutral-600 font-medium flex items-center bg-neutral-100">
                    <x-lucide-calendar class="w-3.5 h-3.5 mr-1" />
                    {{ $task->due_date->format('d M Y') }}
                </div>
            @endif
        </div>
    @endif
</div>