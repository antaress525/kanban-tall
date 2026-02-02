<?php

use Livewire\Component;
use Livewire\Attributes\Title;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

new #[Title('Changer le mot de passe')] class extends Component
{
    public string $currentPassword = '';
    public string $password = '';
    public string $password_confirmation = '';

    public function updatePassword() {
        try {
            $validated = $this->validate([
                'currentPassword' => ['required', 'string', 'current_password'],
                'password' => ['required', 'string', Password::defaults(), 'confirmed'],
            ]);
        } catch (ValidationException $e) {
            $this->reset('currentPassword', 'password', 'password_confirmation');
            throw $e;
        }

        Auth::user()->update([
            'password' => $validated['password'],
        ]);

        $this->reset('currentPassword', 'password', 'password_confirmation');

        $this->dispatch('notify', type: 'success', message: 'Votre mot de passe a ete mis a jour');
    }
};
?>

<div class="p-3.5">
    <!-- Header -->
    <div class="mb-4 sm:mb-6">
        <h1 class="text-2xl font-medium">Parametre</h1>
        <p class="text-neutral-400 text-sm">Gerer vos paramatres</p>
    </div>
    
    <!-- Content -->
    <div class="flex flex-col sm:flex-row sm:items-start gap-6">
        <div class="w-full flex flex-row sm:flex-col sm:max-w-52 space-y-1">
            <x-ui.nav-item wire:navigate :href="route('setting.account')" :active="request()->routeIs('setting.account')" wire:navigate>
                <x-slot:icon>
                    <x-lucide-user class="size-5"/>
                </x-slot:icon>
                Compte
            </x-ui.nav-item>
            <x-ui.nav-item wire:navigate :href="route('setting.password')" :active="request()->routeIs('setting.password')" wire:navigate>
                <x-slot:icon>
                    <x-lucide-shield class="size-5"/>
                </x-slot:icon>
                Securiter
            </x-ui.nav-item>
        </div>
        <div class="flex-1">
            <!-- Change user name -->
            <form wire:submit="updatePassword" class="space-y-3.5 max-w-xs">
                <x-ui.field>
                    <x-ui.label>Ancien mot de passe</x-ui.label>
                    <x-ui.input wire:model="currentPassword" name="currentPassword" size="md" type="password"></x-ui.input>
                    @error('currentPassword')
                        <x-ui.error>{{ $message }}</x-ui.error>
                    @enderror
                </x-ui.field>
                <x-ui.field>
                    <x-ui.label>Nouveau mot de passe</x-ui.label>
                    <x-ui.input wire:model="password" name="password" size="md" type="password"></x-ui.input>
                    @error('password')
                        <x-ui.error>{{ $message }}</x-ui.error>
                    @enderror
                </x-ui.field>
                <x-ui.field>
                    <x-ui.label>Confirmer le nouveau mot de passe</x-ui.label>
                    <x-ui.input wire:model="password_confirmation" name="password_confirmation" size="md" type="password"></x-ui.input>
                </x-ui.field>
                <x-ui.button size="md">
                    <x-ui.spinner class="not-in-data-loading:hidden size-4 fill-white" />
                    Mettre a jour
                </x-ui.button>
            </form>
        </div>
    </div>
</div>