<?php

namespace App\Livewire\Forms\Auth;

use Illuminate\Validation\Rules\Password;
use Livewire\Form;

class RegisterForm extends Form
{
    public string $name = '';

    public string $email = '';

    public string $password = '';

    public function rules(): array {
        return [
            'email' => ['required','email'],
            'name' => ['required','string','min:4', 'max:64'],
            'password' => Password::default()
        ];
    }
    
}
