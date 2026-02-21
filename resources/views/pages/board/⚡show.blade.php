<?php

use Livewire\Component;
use App\Models\Board;
use App\Enum\TaskStatuEnum;
use Livewire\Attributes\Url;
use Illuminate\Validation\Rule;
use App\Enum\TaskPriorityEnum;
use Illuminate\Validation\ValidationException;


new class extends Component
{
    public Board $board;

    public array $columns = [];

    #[Url(as: 'p')]
    public array $priority = [];

    #[Url(as: 'q', except: '')]
    public string $search = '';

    public $date;

    public function mount(Board $board) {
        $this->date = now()->format('Y-m-d');
        $this->board = $board->load(['tasks', 'members']);
        $this->columns = [
            TaskStatuEnum::TODO->value => TaskStatuEnum::TODO->label(),
            TaskStatuEnum::INPROGRESS->value => TaskStatuEnum::INPROGRESS->label(),
            TaskStatuEnum::REVIEW->value => TaskStatuEnum::REVIEW->label(),
            TaskStatuEnum::DONE->value => TaskStatuEnum::DONE->label()
        ];

        try {
            $this->validate();
        } catch(ValidationException $e) {
            $this->reset('priority');
        }
    }

    public function rules(): array {
        return [
            'priority' => ['array', 'nullable'],
            'priority.*' => ['nullable', Rule::enum(TaskPriorityEnum::class)]
        ];
    }

    public function updatedSearch(string $value) {
        $this->dispatch('search-task', search: $value)
            ->component('kanban.column');
    }

    public function updatedPriority(array $value) {
        $this->dispatch('priority-updated', priority: $value)
             ->component('kanban.column');
    }

    public function render()
    {
        return $this->view()
            ->title($this->board->name);
    }
};
?>

<div class="p-3.5 flex flex-col h-full">
    <!-- Board Header -->
    <div class="flex items-center justify-between mb-4">
        <div class="flex items-center gap-x-3">
            <div 
                class="size-8 font-medium grid place-items-center rounded-lg" style="background-color: {{ $board->color.'26' }}; color: {{ $board->color }}; background-opacity: 0.1;"
            >
                {{ strtoupper(substr($board->name, 0, 1)) }}
            </div>
            <!-- Board name desktop -->
            <h3 class="text-lg hidden sm:block sm:text-xl truncate font-medium">{{ $board->name }}</h3>

            <!-- Board name mobile -->
            <h3 class="text-lg sm:hidden sm:text-xl truncate font-medium">{{ Str::limit(Str::ucfirst($board->name), 9) }}</h3>
        </div>
        <div class="flex items-center gap-x-2">
            <x-ui.avatar-group>
                @foreach ($board->members()->limit(3)->get() as $member)
                    <img src="{{ $member->getAvatarUrl() }}" class="border-2 border-white rounded-lg" alt="">
                @endforeach
                @if ($board->members()->count() > 3)
                    <x-ui.avatar-group-count count="{{ $board->members()->count() - 3 }}" />
                @endif
            </x-ui.avatar-group>
            @can('invite', $board)
                <div class="hidden sm:block">
                    <x-ui.button 
                        @click="$dispatch('open-modal', {type: 'center', component: 'modals.invite-user', size: 'xl', props: {board_id: '{{ $board->id }}' } })"
                        variant="secondary" 
                        size="md" 
                        class="font-medium"
                    >
                        <x-lucide-user-plus class="size-4 text-neutral-500"/>
                        Inviter
                    </x-ui.button>
                </div>
            @endcan

            <div class="hidden sm:block">
                <x-ui.button variant="secondary" size="md" class="font-medium">
                    <x-lucide-settings class="size-4 text-neutral-500"/>
                    Parametre
                </x-ui.button>
            </div>

            <!-- Mobile button -->
            <x-ui.icon-button size="md" class="sm:hidden">
                <x-lucide-settings class="size-4 text-neutral-500"/>
            </x-ui.icon-button>
            <x-ui.icon-button size="md" class="sm:hidden">
                <x-lucide-user-plus class="size-4 text-neutral-500"/>
            </x-ui.icon-button>
        </div>
    </div>

    <!-- Board action -->
    <div class="flex items-center gap-x-2 justify-end mb-6">
        <div x-data="{date: '2026-01-05'}">
            <x-ui.date-picker wire:model="date" />
        </div>
        @include('pages.board.partials.filter')

        <x-ui.input wire:model.live.debounce.500ms="search" name="search" size="md" class="w-full sm:w-3xs" placeholder="Recherche">
            <x-slot:prefix>
                <x-ui.spinner class="size-4 fill-neutral-400" wire:loading wire:target="search" />
                <x-lucide-search class="size-4 text-neutral-500" wire:loading.remove wire:target="search" />
            </x-slot:prefix>
        </x-ui.input>
    </div>

    <!-- Columns desktop-->
    <div class="flex-1 gap-x-2 hidden xl:flex">
        @foreach ($columns as $status => $title)
            <livewire:kanban.column
                :title="$title"
                :status="$status"
                :board="$board"
                :priority="$priority"
                :search="$search"
            />
        @endforeach 
    </div>


    <!-- Columns mobile -->
    <div class="embla xl:hidden" >
        <div class="embla__viewport overflow-hidden">
            <div class="flex flex-1 gap-x-2 .embla__container">
                @foreach ($columns as $status => $title)
                    <livewire:kanban.column
                        :title="$title"
                        :status="$status"
                        :board="$board"
                        :priority="$priority"
                        :search="$search"
                    />
                @endforeach
            </div>
        </div>
    </div>
</div>