<?php

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;
use Illuminate\Database\Eloquent\Builder;


new #[Title('Mes tableau')] class extends Component
{
    use WithPagination;

    #[Url(except: '', as: 'q')]
    public string $search = '';

    protected $listeners = [
        'board-created' => '$refresh',
    ];

    #[Computed]
    public function boards() {
        return auth()->user()
            ->boards()
            ->when($this->search, function(Builder $query, string $search) {
                return $query->whereLike('name', "%$search%");
            })
            ->paginate(5);
    }

    public function updatedSearch() {
        $this->resetPage();
    }
};
?>

<div class="w-full sm:max-w-2xl mx-auto p-4">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8 sm:mb-13">
        <h2 class="text-lg md:text-xl font-poppins font-medium">Projets</h2>
        <x-ui.button size="md" class="font-medium" @click="$dispatch('open-modal', {type: 'center', component: 'modals.create-board', size: 'xs'})">
            Creer un projet
        </x-ui.button>
    </div>

    <!-- Search -->
    <div class="mb-9 sm:mb-14 flex items-center gap-x-2">
        <x-ui.input name="searh" wire:model.live.debounce.500ms="search" size="md" placeholder="Recherche">
            <x-slot:prefix>
                <x-ui.spinner wire:loading wire:target="search" class="size-4" />
                <x-lucide-search wire:loading.remove wire:target="search"  class="size-4 text-neutral-500"/>
            </x-slot:prefix>
        </x-ui.input>
        <x-ui.button variant="secondary" size="md" class="font-medium">
            <x-lucide-sliders class="size-4 text-neutral-500"/>
            Filtres
        </x-ui.button>
    </div>

    <!-- Boards -->
    <div class="space-y-4 sm:space-y-6 md:space-y-8">
        @if ($this->boards->isNotEmpty())
            <div>
                @foreach ($this->boards as $board)
                    <livewire:board-item :wire:key="$board->id" :board="$board" @board-deleted="$refresh" />
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
                <h3 class="font-medium">Votre espace de travail est vide</h3>
                <p class="text-neutral-500 mt-1 text-sm text-pretty">Commencez dès maintenant en créant un projet et transformez vos <br class="hidden sm:block"/> idées en actions concrètes.</p>
                <x-ui.button size="md" class="mt-6 font-medium">Commercer un projet</x-ui.button>
            </div>
        @endif
    </div>
</div>