<?php

use Livewire\Component;
use App\Models\Task;
use App\Livewire\Forms\Task\UpdateForm;

new class extends Component
{
    public UpdateForm $form;
    public Task $task;

    public function mount(array $props) {
        $this->task = Task::findOrFail($props['task_id']);
        $this->form->setTask($this->task);
    }

    public function basicUpdate() {
        $attributes = $this->validate();
        $this->task->update($attributes);
        $this->dispatch("task-updated.{$this->task->id}")
            ->component('kanban.item');
        $this->dispatch('notify', type: 'success', message: 'La tache a ete mise a jour');
    }

}
?>


<div class="h-full">
    <!-- Header -->
    <div class="flex items-center justify-between p-3.5 border-b border-neutral-200">
        <h3 class="text-sm font-medium">Détails de la tâche</h3>
        <x-ui.icon-button
            size="xs"
            @click="$dispatch('close-modal')"
        >
            <x-lucide-x class="size-3.5 text-neutral-500"/>
        </x-ui.icon-button>
    </div>

    <!-- Content -->
    <div class="p-3.5">
        <form wire:submit="basicUpdate" class="space-y-2.5">
            <x-ui.field>
                <x-ui.label for="title">Titre</x-ui.label>
                <x-ui.input wire:model="form.title" size="md" name="title" id="title" placeholder="Titre de la tâche"/>
                @error('form.title')
                    <x-ui.error>{{ $message }}</x-ui.error>
                @enderror
            </x-ui.field>
            <x-ui.field>
                <x-ui.label for="description">Description</x-ui.label>
                <x-ui.textarea
                    wire:model="form.description"
                    name="description"
                    id="description"
                    class="w-full"
                    placeholder="Description de la tâche"
                />
                @error('form.description')
                    <x-ui.error>{{ $message }}</x-ui.error>
                @enderror
            </x-ui.field>
             <x-ui.button size="md" type="submit" class="mt-2 data-loading:opacity-50">
                <x-ui.spinner class="not-in-data-loading:hidden size-3.5 fill-white" />
                <span>Enregistrer</span>
            </x-ui.button>
        </form>
    </div>
</div>
