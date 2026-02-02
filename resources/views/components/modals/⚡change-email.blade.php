<?php

use Livewire\Component;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Mail;
use App\Mail\ConfirmEmailChangeMail;
use App\Models\EmailChange;
use Illuminate\Support\Str;

new class extends Component
{

    #[Validate('required|email|unique:users,email|unique:email_changes,new_email')]
    public string $newEmail = '';

    public function changeEmail() {
        $this->validate();
        $user = auth()->user();


        EmailChange::where('user_id', auth()->id())->delete();

        $emailChange = $user->emailChanges()->create([
            'new_email' => $this->newEmail,
            'token' => Str::random(60),
            'expires_at' => now()->addMinutes(30),
        ]);

        Mail::to($this->newEmail)
            ->send(new ConfirmEmailChangeMail($emailChange));

        $this->reset('newEmail');

        $this->dispatch('close-modal');

        $this->dispatch(
            'notify',
            type: 'success',
            message: 'Un mail de confirmation a ete envoyer, vueillez consulter votre boite mail'
        );
    }
    
};
?>

<form wire:submit="changeEmail" class="p-3.5">
    <div class="flex items-center gap-x-2">
        <x-ui.input size="md" wire:model="newEmail" name="new email" placeholder="Enter votre nouvelle adresse"></x-ui.input>
        <x-ui.button size="md" type="submit">Changer</x-ui.button>
    </div>
    @error('newEmail')
        <x-ui.error class="mt-0.5">{{ $message }}</x-ui.error>
    @enderror
</form>