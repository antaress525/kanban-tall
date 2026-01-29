<?php

namespace App\Livewire\Forms\Task;

use Livewire\Attributes\Validate;
use Livewire\Form;

class CreateForm extends Form
{
    public string $title = '';

    public function rules(): array {
        return [
            'title' => ['required', 'string', 'max:128'],
        ];
    }
}
