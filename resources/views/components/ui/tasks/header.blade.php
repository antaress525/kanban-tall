@props(['status', 'boardId'])

<div {{ $attributes->merge(['class' => 'h-9 p-2 text-white font-medium flex items-center justify-between rounded-lg']) }}>
    {{ $slot }}
    <button 
        class="cursor-pointer"
        @click="$dispatch('open-modal', {type: 'center', component: 'modals.task.create', size: 'sm', props: {board_id: '{{ $boardId }}', status: '{{ $status }}'}})"
    >
        <x-lucide-plus class="size-4 text-white"/>
    </button>
</div>