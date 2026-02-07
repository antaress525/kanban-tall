<?php

use Livewire\Component;
use App\Models\Board;
use Livewire\Attributes\On;

new class extends Component
{
    public Board $board;

    public function mount(Board $board) {
        $this->board = $board;
    }

    #[On('board-updated.{board.id}')]
    public function refreshBoard() {
        $this->board->refresh();
    }

    public function delete() {
        $this->authorize('delete', $this->board);
        $this->board->delete();
        $this->dispatch('board-deleted');
        $this->dispatch('notify', type: 'success', message: 'Le projet a été supprimé.');
    }

};
?>

@placeholder
    <div class="px-3 py-2 flex items-center justify-between border-b border-neutral-200 animate-pulse">
        <div class="flex items-center gap-x-4.5">
            <div class="size-8 rounded-lg bg-neutral-200"></div>

            <div class="h-4 w-32 rounded bg-neutral-200"></div>
        </div>

        <div class="hidden sm:flex items-center gap-x-2">
            <div class="h-3 w-20 rounded bg-neutral-200"></div>

            <div class="size-8 rounded bg-neutral-200"></div>
            <div class="size-8 rounded bg-neutral-200"></div>
        </div>
    </div>

@endplaceholder

<div class="group odd:bg-white even:bg-gray-50 px-3 py-2 flex items-center justify-between border-b border-neutral-200 relative">
    <div class="flex items-center gap-x-4.5">
        <div class="size-8 font-medium grid place-items-center rounded-lg" style="background-color: {{ $board->color.'26' }}; color: {{ $board->color }}; background-opacity: 0.1;">
            {{ strtoupper(substr($board->name, 0, 1)) }}
        </div>
        <a href="{{ route('board.show', $board) }}" wire:navigate class="text-sm font-medium after:content[''] after:absolute after:inset-0">{{ $board->name }}</a>
    </div>

    <!-- Desktop -->
    <div class="z-10 hidden sm:flex items-center gap-x-2">
        <span class="text-neutral-400 text-sm group-hover:hidden">{{ $board->created_at->diffForHumans() }}</span>

        <!-- Edit -->
        <x-ui.icon-button 
            size="sm"
            class="data-loading:bg-neutral-50 hidden group-hover:grid" 
            @click="$dispatch('open-modal', {type: 'center', component: 'modals.update-board', size: 'xs', props: { board_id: '{{ $board->id }}' }})" 
            title="Modifier"
        >
            <x-lucide-pencil class="size-4 text-neutral-400 in-data-loading:hidden" />
            <x-ui.spinner class="size-4 fill-neutral-400 not-in-data-loading:hidden" />
        </x-ui.icon-button>

        <!-- Delete -->
        <x-ui.icon-button size="sm" class="data-loading:bg-neutral-50 hidden group-hover:grid" wire:click="delete" title="Supprimer">
            <x-lucide-trash class="size-4 text-neutral-400 in-data-loading:hidden" />
            <x-ui.spinner class="size-4 fill-neutral-400 not-in-data-loading:hidden" />
        </x-ui.icon-button>
    </div>

    <!-- Mobile -->
    <div class="z-10 flex items-end gap-x-1 sm:hidden">
        <!-- Edit -->
        <x-ui.icon-button 
            class="data-loading:bg-neutral-50" 
            @click="$dispatch('open-modal', {type: 'center', component: 'modals.update-board', size: 'xs', props: { board_id: '{{ $board->id }}' }})"
            title="Modifier"
            size="sm"
        >
            <x-lucide-pencil class="size-4 text-neutral-400 in-data-loading:hidden" />
            <x-ui.spinner class="size-4 fill-neutral-400 not-in-data-loading:hidden" />
        </x-ui.icon-button>

        <!-- Delete -->
        <x-ui.icon-button size="sm" class="data-loading:bg-neutral-50" wire:click="delete" title="Supprimer">
            <x-lucide-trash class="size-4 text-neutral-400 in-data-loading:hidden" />
            <x-ui.spinner class="size-4 fill-neutral-400 not-in-data-loading:hidden" />
        </x-ui.icon-button>
    </div>
</div>