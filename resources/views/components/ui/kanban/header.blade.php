@props(['status', 'boardId', 'title', 'count'])

<div {{ $attributes->merge(['class' => 'h-9 p-2 font-medium text-sm flex items-center justify-between rounded-lg']) }}>
    <div class="flex items-center">
        {{ $title }}
        <span class="text-neutral-500 ml-1 task__count">
            @if ($count)
                {{ $count }} 
            @endif
        </span>
    </div>
    <button 
        class="cursor-pointer"
        @click="$dispatch('open-modal', {type: 'center', component: 'modals.task.create', size: 'sm', props: {board_id: '{{ $boardId }}', status: '{{ $status }}'}})"
    >
        <x-lucide-plus class="size-4"/>
    </button>
</div>