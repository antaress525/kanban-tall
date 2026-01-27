<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class BoardForm extends Form
{
    public string $name = '';

    public function rules(): array {
        return [
            'name' => ['required','string','min:4', 'max:52'],
        ] ;
    }
}
