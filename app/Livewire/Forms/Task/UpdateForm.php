<?php

namespace App\Livewire\Forms\Task;

use App\Models\Task;
use Livewire\Attributes\Validate;
use Livewire\Form;

class UpdateForm extends Form
{
    #[Validate('required|max:155|min:4')]
    public string $title = "";

    #[Validate("nullable|max:155|min:12")]
    public string $description = "";

    public function setTask(Task $task) {
        $this->title = $task->title;
        $this->description = $task->description ?? '';
    }
}
