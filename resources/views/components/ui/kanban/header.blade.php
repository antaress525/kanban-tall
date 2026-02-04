@props(['status', 'boardId', 'title'])

<div {{ $attributes->merge(['class' => 'h-9 p-2 font-medium text-sm flex items-center justify-between rounded-lg']) }}>
    {{ $title }}
    <button 
        class="cursor-pointer"
        @click="$dispatch('open-modal', {type: 'center', component: 'modals.task.create', size: 'sm', props: {board_id: '{{ $boardId }}', status: '{{ $status }}'}})"
    >
        <x-lucide-plus class="size-4"/>
    </button>
</div>