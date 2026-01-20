<?php

use Livewire\Component;
use App\Models\Board;

new class extends Component
{
    public Board $board;

    public function mount(Board $board) {
        $this->board = $board;
    }
};
?>

<div class = "odd:bg-white even:bg-gray-50 px-3 py-2 flex items-center justify-between border-b border-neutral-200 relative">
    <a href="{{ route('board.show', $board) }}" class="absolute inset-0" wire:navigate></a>
    <div class="flex items-center gap-x-4.5">
        <div class="size-8 font-medium grid place-items-center rounded-lg" style="background-color: {{ $board->color.'26' }}; color: {{ $board->color }}; background-opacity: 0.1;">
            {{ strtoupper(substr($board->name, 0, 1)) }}
        </div>
        <p class="text-sm font-medium">{{ $board->name }}</p>
    </div>
    <span class="text-neutral-400 text-sm">{{ $board->created_at->diffForHumans() }}</span>
</div>