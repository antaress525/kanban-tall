<?php

namespace App\Livewire\Forms\Auth;


use Illuminate\Validation\Rules\Password;
use Livewire\Form;

class LoginForm extends Form
{
    public string $password = '';

    public string $email = '';

    protected function rules(): array
    {
        return [
            'email' => ['required', 'email', 'min:6'],
            'password' => ['required',
                Password::default()
            ],
        ];
    }
}
