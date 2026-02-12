<?php

use Livewire\Component;
use App\Models\Task;
use Livewire\Attributes\Computed;
use Illuminate\Support\Collection;
use App\Models\User;

new class extends Component
{
    public Task $task;

    public string $search = '';

    #[Computed]
    public function members() {
        if(!$this->search) {
            return new Collection();
        }
        return $this->task->board
            ->members()
            ->whereLike('users.name', '%'.$this->search.'%')
            ->whereNotIn('users.id', $this->task->assignees->pluck('id'))
            ->get();
    }

    public function assign(User $user) {
        if (! $this->task->board->members->contains($user->id)) {
            return;
        }

        if (! $this->task->assignees->contains($user->id)) {
            $this->task->assignees()->attach($user->id);
        }

        $this->task->load('assignees');
        $this->dispatch("member-added.{$this->task->id}")
            ->component('kanban.item');
    }

    public function remove(User $user) {
        $this->task->assignees()->detach($user->id);
        $this->task->load('assignees');
        $this->dispatch("member-removed.{$this->task->id}")
            ->component('kanban.item');
    }
};
?>

<div class="space-y-3.5">
    <!-- Search --->
    <div class="relative">
        <!-- Input -->
        <x-ui.input
            wire:model.live.debounce.500ms="search"
            size="md"
            placeholder="Nom du membres"
            name="search-members"
        >
            <x-slot:prefix>
                <x-ui.spinner
                    class="size-4 fill-neutral-400"
                    wire:loading
                    wire:target="search"
                />
                <x-lucide-search
                    class="size-4 text-neutral-500"
                    wire:loading.remove
                    wire:target="search"
                />
            </x-slot:prefix>
        </x-ui.input>
        <!-- Result -->
        @if ($search)
            <div
                wire:transition
                class="abolute flex flex-col overflow-x-hidden inset-x-0 mt-1 min-h-13 border border-neutral-200 shadow-lg bg-white rounded-lg"
            >
                @forelse ($this->members as $member)
                    <x-ui.member wire:click="assign({{ $member->id }})" :user="$member" />
                @empty
                    <div class="w-full flex-1 flex items-center justify-center">
                        <span class="text-neutral-400 text-center align-middle text-sm font-medium">Aucun utilisateur</span>
                    </div>
                @endforelse
            </div>
        @endif
    </div>

    <!--  Assigned Members  -->
    <div>
        <div class="text-xs text-neutral-500 mb-2">
            Membres assignés
        </div>

        <div class="flex flex-wrap gap-2 border border-neutral-200 rounded-lg min-h-14 p-3.5">

            @forelse($task->assignees as $assignee)
                <div
                    class="flex items-center gap-x-3 bg-white shadow-xs border border-neutral-200 px-1.5 py-1 rounded-full text-xs"
                >
                    <img
                        src="{{ $assignee->getAvatarUrl() }}"
                        class="size-6 rounded-full"
                    >
                    <span>{{ $assignee->name }}</span>

                    <button
                        wire:click="remove({{ $assignee->id }})"
                        class="hover:text-red-500"
                    >
                        ✕
                    </button>
                </div>
            @empty
                <div class="text-sm text-neutral-400">
                    Personne assigné
                </div>
            @endforelse

        </div>
    </div>
</div>