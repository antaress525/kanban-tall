<?php

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use App\Livewire\Forms\Auth\LoginForm;

new #[Layout('layouts::guest'), Title('Se connecter')] class extends Component
{
    public LoginForm $form;

    public function login() {
        $this->form->validate();

        if (!auth()->attempt([
            'email' => $this->form->email,
            'password' => $this->form->password
        ])) {
            $this->addError('email', 'Les identifiants sont incorrects.');
            return;
        }

        if ($token = session()->pull('invitation_token')) {
            return redirect()->route('boards.invitations.accept', $token);
        }

        session()->regenerate();
        return $this->redirectIntended('/', true);
    }
};
?>

<form wire:submit.prevent="login" class="flex flex-col justify-center bg-white border border-neutral-200 rounded-lg w-sm p-6 space-y-4">
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
        Se connecter
    </x-ui.button>
    <span class="text-center text-sm text-neutral-400">
        Vous n'avez pas de compte?
        <x-ui.link class="text-black" href="{{ route('auth.register') }}" wire:navigate>S'inscrire</x-ui.link>
    </span>
</form>