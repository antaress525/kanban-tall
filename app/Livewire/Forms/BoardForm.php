<?php

namespace App\Livewire\Forms;

use App\Models\Board;
use Livewire\Attributes\Validate;
use Livewire\Form;

class BoardForm extends Form
{
    public ?Board $board;
    public string $name = '';

    public function setBoard(Board $board)
    {
        $this->board = $board;

        $this->name = $board->name;
    }

    public function rules(): array {
        return [
            'name' => ['required','string','min:4', 'max:52'],
        ] ;
    }
}
