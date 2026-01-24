<?php

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Livewire\Forms\Auth\RegisterForm;
use App\Models\User;

new #[Layout('layouts::guest'), Title('S\'inscrire')] class extends Component
{
    public RegisterForm $form;

    public function register()
    {
        $this->validate();

        $user = User::create([
            'name' => $this->form->name,
            'email' => $this->form->email,
            'password' => bcrypt($this->form->password),
        ]);

        auth()->login($user);

        return $this->redirectRoute('dashboard', navigate: true);
    }
};
?>

<form wire:submit.prevent="register" class="flex flex-col justify-center bg-white border border-neutral-200 rounded-lg w-sm p-6 space-y-4">
     <x-ui.field>
        <x-ui.label>Nom</x-ui.label>
        <x-ui.input type="text" wire:model="form.name" name="name" placeholder="Votre nom" required autofocus />
        @error('form.name')
            <x-ui.error>{{ $message }}</x-ui.error>
        @enderror
    </x-ui.field>
    <x-ui.field>
        <x-ui.label>Email</x-ui.label>
        <x-ui.input type="email" wire:model="form.email" name="email" placeholder="Votre email" required autofocus />
        @error('form.email')
            <x-ui.error>{{ $message }}</x-ui.error>
        @enderror
    </x-ui.field>
    <x-ui.field>
        <x-ui.label>Mot de passe</x-ui.label>
        <x-ui.password-input wire:model="form.password" name="password" placeholder="Votre mot de passe" required />
        @error('form.password')
            <x-ui.error>{{ $message }}</x-ui.error>
        @enderror
    </x-ui.field>
    <x-ui.button type="submit" class="w-full mt-4 data-loading:opacity-50">
        <x-ui.spinner class="not-in-data-loading:hidden size-4.5 fill-white" />
        Accéder à Kanban App
    </x-ui.button>
    <span class="text-center text-sm text-neutral-400">
        Vous avez deja un compte?
        <x-ui.link class="text-black" href="{{ route('auth.login') }}" wire:navigate>Se connecter</x-ui.link>
    </span>
    
</form>