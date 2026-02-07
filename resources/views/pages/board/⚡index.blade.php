<?php

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;
use Illuminate\Database\Eloquent\Builder;


new #[Title('Mes tableau')] class extends Component
{
    use WithPagination;

    #[Url(except: '', as: 'q')]
    public string $search = '';

    #[Locked, Url(as: 'tab')]
    public string $activeTab = 'my_board';
    protected array $allowTab = ['my_board', 'collab_board'];

    public function setActiveTab(string $tab) {
        if(in_array($tab, $this->allowTab)) {
            $this->activeTab = $tab;
        } else{
            $this->reset('activeTab');
        }
    }

    protected $listeners = [
        'board-created' => '$refresh',
    ];

    #[Computed]
    public function boards() {
        if($this->activeTab === 'my_board') {
            return auth()->user()
                ->boards()
                ->when($this->search, function(Builder $query, string $search) {
                    return $query->whereLike('name', "%$search%");
                })
                ->latest()
                ->paginate(5);
        }
        return auth()->user()
            ->collaborativeBoards()
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
    <div class="mb-4 sm:mb-8 flex items-center gap-x-2">
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

    <!-- Tabs -->
    @php
        $classes = 'border-b-2 border-indigo-500 bg-indigo-500/5 text-indigo-500'
    @endphp

    <div class="h-12 flex items-center border-b border-neutral-100 mb-6 sm:mb-12 w-full text-black text-sm font-medium">
        <button 
            class="flex-1 h-full flex items-center justify-center cursor-pointer {{ $activeTab === 'my_board' ? $classes : '' }}"
            wire:click="setActiveTab('my_board')"
        >
            Mes tableaux
        </button>
        <button 
            class="flex-1 h-full flex items-center justify-center cursor-pointer {{ $activeTab === 'collab_board'? $classes : '' }}"
            wire:click="setActiveTab('collab_board')"
        >
            Mes collaborations
        </button>
    </div>

    <!-- Boards -->
    @if ($activeTab === 'collab_board')
        @include('pages.board.partials.collaborative-board')
    @else
        @include('pages.board.partials.my-board')
    @endif
    
</div>