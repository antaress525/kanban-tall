<?php

use Livewire\Component;
use App\Models\Task;
use App\Livewire\Forms\Task\UpdateForm;
use Illuminate\Validation\Rule;
use APP\Enum\TaskPriorityEnum;
use Livewire\Attributes\Renderless;

new class extends Component
{
    public UpdateForm $form;
    public Task $task;
    public $priority;
    public $dueDate;

    public function mount(array $props) {
        $this->task = Task::findOrFail($props['task_id']);
        $this->authorize('update', $this->task);
        $this->form->setTask($this->task);
        $this->priority = $this->task->priority;
        $this->dueDate = $this->task->due_date;
    }

    public function basicUpdate() {
        $attributes = $this->validate();
        $this->task->update($attributes);
        $this->dispatch("task-updated.{$this->task->id}")
            ->component('kanban.item');
        $this->dispatch('notify', type: 'success', message: 'La tache a ete mise a jour');
    }

    public function updatedPriority($value) {
        $atributes = $this->validate( [
            'priority' =>  [Rule::enum(TaskPriorityEnum::class)]
        ]);
        $this->task->update($atributes);
        $this->dispatch("priority-updated.{$this->task->id}");
        $this->skipRender();
    }

    public function updateDueDate($value) {
        $this->validate([
            'dueDate' => ['nullable', Rule::date()->after(now())]
        ]);
        $this->task->update(['due_date' => $value]);
        $this->dispatch("due-date-updated.{$this->task->id}");
    }

    public function cleanDueDate() {
        $this->task->update(['due_date' => null]);
        $this->dispatch("due-date-updated.{$this->task->id}");
    }

    public function render()
    {
        return $this->view();
    }

}
?>


<div class="h-full flex flex-col">
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
    <div class="p-3.5 flex-1 overflow-y-auto space-y-6">
        <!-- Basic info -->
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

        <x-ui.separator />

        <!-- Priority -->
        <div class="space-y-3.5">
            <!-- Header -->
            <div class="flex items-center gap-x-2">
                <h3 class="text-[14.5px] font-medium">Prioriter</h3>
                @error('priority')
                    <x-ui.error>{{ $message }}</x-ui.error>
                @enderror
            </div>
            <div class="grid grid-cols-2 items-center gap-2 flex-wrap">
                @foreach (App\Enum\TaskPriorityEnum::cases() as $priority)
                    <x-ui.radio-card wire:model.live.throttle.500ms="priority" name="priority" :label="$priority->label()" :value="$priority->value" :description="$priority->description()" />
                @endforeach
            </div>
        </div>

        <x-ui.separator />

        <!-- Due date -->
        <div class="space-y-3.5">
            <!-- Header -->
            <div class="flex items-center gap-x-2">
                <h3 class="text-[14.5px] font-medium">Date d'échéance</h3>
                @error('dueDate')
                    <x-ui.error>{{ $message }}</x-ui.error>
                @enderror
            </div>
            <div
                x-data="{
                    dueDate: $wire.dueDate,
                    updateDueDate(value) {
                        this.$wire.updateDueDate(value);
                    },
                    cleanDueDate() {
                        this.dueDate = null;
                        this.$wire.cleanDueDate();
                    }
                }"
                x-init="$watch('dueDate', value => updateDueDate(value))"
                class="flex items-center gap-2 flex-wrap">
                <x-ui.date-picker x-model.throttle.500ms="dueDate" />
                <x-ui.button
                    size="md"
                    x-show="dueDate"
                    x-transition
                    variant="secondary"
                    @click="cleanDueDate"
                >
                    <x-lucide-x class="size-3.5 text-neutral-500"/>
                    Supprimer
                </x-ui.button>
            </div>
        </div>

        <x-ui.separator />

        <!-- Assign to -->
        <div class="space-y-3.5">
            <!-- Header -->
            <div class="flex items-center gap-x-2">
                <h3 class="text-[14.5px] font-medium">Assigner la tache</h3>
            </div>
            <livewire:assign-members :task="$task" />
        </div>
    </div>
</div>
