<?php

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Rule;
use App\Models\User;

new #[Title('Parametre')] class extends Component
{
    public User $user;
    
    #[Rule('required')]
    public string $name = '';
    public string $email = '';

    public function mount() {
        $this->user = auth()->user();
        $this->name = $this->user->name;
        $this->email = $this->user->email;
    }

    public function changeName() {
        $attributes = $this->validate();
        $this->user->update($attributes);
        $this->dispatch('notify', type: 'success', message: 'Votre nom a ete mis a jour');
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
            <x-ui.nav-item wire:navigate :href="route('setting.account')" wire:navigate>
                <x-slot:icon>
                    <x-lucide-shield class="size-5"/>
                </x-slot:icon>
                Securiter
            </x-ui.nav-item>
        </div>
        <div class="flex-1 space-y-6">
            <!-- Change user name -->
            <form wire:submit="changeName">
                <div class="flex items-end gap-x-2">
                    <x-ui.field>
                        <x-ui.label>Nom complet</x-ui.label>
                        <x-ui.input wire:model="name" name="name" size="md" placeholder="Enter votre nom"></x-ui.input>
                    </x-ui.field>
                    <x-ui.button x-bind:disabled="!$wire.$dirty('name')" size="md">
                        <x-ui.spinner class="not-in-data-loading:hidden size-4 fill-white" />
                        Modifier
                    </x-ui.button>
                </div>
                
                @error('name')
                        <x-ui.error>{{ $message }}</x-ui.error>
                @enderror
            </form>

            <!-- Change email -->
            <div class="space-y-3.5">
                <div>
                    <h2 class="">Changer votre adress mail</h2>
                    <p class="text-sm text-neutral-400">Se parametre vos permet de changer l'adress mail associer avec votre compte</p>
                </div>
                <div class="flex items-center gap-x-2">
                    <x-ui.input name="change email" value="{{ $email }}" size="md" disabled></x-ui.input>
                    <x-ui.button size="md" @click="$dispatch('open-modal', {type: 'center', component: 'modals.change-email', size: 'xs'})">Changer</x-ui.button>
                </div>
            </div>

            <!-- Delete account -->
            <div class="space-y-3.5 mt-16">
                <hr class="text-neutral-200">
                <div>
                    <h2 class="">Supprimer votre compte</h2>
                    <p class="text-sm text-neutral-400 text-pretty">Vous devez comprendre que cette action et irreversible et supprimera toutes vos donnees</p>
                </div>
                <x-ui.button size="md" variant="danger">Supprimer</x-ui.button>
            </div>
        </div>
    </div>
</div>

<script>
    
</script>