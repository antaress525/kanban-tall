<?php

use Livewire\Component;
use App\Models\Board;

new class extends Component
{
    public Board $board;

    public function mount(Board $board) {
        $this->board = $board;
    }

    public function delete() {
        $this->authorize('delete', $this->board);
        $this->board->delete();
        $this->dispatch('board-deleted');
    }
};
?>

<div class="group odd:bg-white even:bg-gray-50 px-3 py-2 flex items-center justify-between border-b border-neutral-200 relative">
    <div class="flex items-center gap-x-4.5">
        <div class="size-8 font-medium grid place-items-center rounded-lg" style="background-color: {{ $board->color.'26' }}; color: {{ $board->color }}; background-opacity: 0.1;">
            {{ strtoupper(substr($board->name, 0, 1)) }}
        </div>
        <a href="{{ route('board.show', $board) }}" wire:navigate class="text-sm font-medium after:content[''] after:absolute after:inset-0">{{ $board->name }}</a>
    </div>

    <!-- Desktop -->
    <div class="z-10 hidden sm:flex items-center gap-x-3">
        <span class="text-neutral-400 text-sm group-hover:hidden">{{ $board->created_at->diffForHumans() }}</span>
        <x-ui.icon-button class="data-loading:bg-neutral-50 hidden group-hover:grid" wire:click="delete" title="Supprimer">
            <x-lucide-trash class="size-4 text-neutral-400 in-data-loading:hidden" />
            <x-ui.spinner class="size-4 fill-neutral-400 not-in-data-loading:hidden" />
        </x-ui.icon-button>
    </div>

    <!-- Mobile -->
    <div class="z-10 sm:hidden">
        <x-ui.icon-button class="data-loading:bg-neutral-50" wire:click="delete" title="Supprimer">
            <x-lucide-trash class="size-4 text-neutral-400 in-data-loading:hidden" />
            <x-ui.spinner class="size-4 fill-neutral-400 not-in-data-loading:hidden" />
        </x-ui.icon-button>
    </div>
</div>