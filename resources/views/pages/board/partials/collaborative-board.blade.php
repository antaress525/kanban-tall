<div class="space-y-4 sm:space-y-6 md:space-y-8">
    @if ($this->boards->isNotEmpty())
        <div>
            @foreach ($this->boards as $board)
                <livewire:board-item :wire:key="$board->id" :board="$board" @board-deleted="$refresh" lazy />
            @endforeach
        </div>
        <div>
            {{ $this->boards->links() }}
        </div>
    @else
        <!-- Empty state -->
        <div class="text-center py-10">
            <div class="p-3.5 mx-auto mb-4 md:mb-6 border border-neutral-200 rounded-xl grid place-items-center shadow-xl w-max">
                <x-lucide-folder class="size-6" />
            </div>
            <h3 class="font-medium">Espace collaboratif est vide</h3>
            <p class="text-neutral-500 mt-1 text-sm text-pretty">Vous n'avez pas recus d'invitation pour avoir acces <br class="hidden sm:block"/> a d'autres espaces.</p>
        </div>
    @endif
</div>