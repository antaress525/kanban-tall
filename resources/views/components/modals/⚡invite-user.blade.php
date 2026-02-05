<?php

use Livewire\Component;
use App\Models\User;
use Livewire\Attributes\Computed;
use App\Models\Board;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\BoardInvitationMail;

new class extends Component
{
    public string $search = '';
    public ?Board $board;

    public function mount(array $props) {
        $this->board = Board::findOrFail($props['board_id']);
        $this->authorize('invite', $this->board);
    }

    #[computed]
    public function users() {
        if(empty($this->search)) {
            return collect();
        }
        return User::where('id', '!=', auth()->id() )
            ->where(function($query) {
                $query->where('email', 'like', '%'.$this->search.'%')
                    ->orWhere('name', 'like', '%'.$this->search.'%');
            })
            ->whereNotIn('email', function($query) {
                $query->select('email')
                    ->from('invitations')
                    ->where('board_id', $this->board->id);
            })
            ->get();
    }

    private function isUserAlreadyInvited(string $email): bool {
        return $this->board->invitations()
            ->where('email', $email)
            ->where('status', 'pending')
            ->exists();
    }

    private function isAuthEmail(string $email): bool {
        return auth()->user()->email === $email;
    }

    private function isEmailExists(string $email): bool {
        return User::where('email', $email)->exists();
    }

    public function invite(string $email) {

        // dd($email);
        $emailExists = $this->isEmailExists($email);
        $authEmail = $this->isAuthEmail($email);

        if (!$emailExists) {
            // $this->error = "L'utilisateur avec cet email n'existe pas.";
            return;
        }
        if ($authEmail) {
            // $this->error = "Vous ne pouvez pas vous inviter vous-même.";
            return;
        }

        $exists = $this->isUserAlreadyInvited($email);

        if ($exists) {
            // $this->error = "Une invitation est déjà en attente pour cet utilisateur.";
            return;
        }

        $invitation = $this->board->invitations()->create([
            'email' => $email,
            'token' => Str::uuid(),
            'status' => 'pending',
            'expires_at' => now()->addDays(7),
            'invited_by' => auth()->id(),
        ]);

        Mail::to($email)->send(new BoardInvitationMail( $invitation) );

        $this->reset('search');
    }
};
?>

<div class="flex p-1 min-h-120 max-h-120">
    <!-- Invite User Section -->
    <div class="p-3.5 flex-1 flex flex-col">
        <div>
            <h2 class="font-medium">Ajouter un nouveau membre</h2>
            <p class="text-neutral-400 text-sm">Recherchez un collaborateur et envoyez-lui une invitation.</p>
        </div>
        
        <!-- Search Input -->
        <div class="flex items-center gap-x-2 mt-8">
            <x-ui.input 
                placeholder="Rechercher un utilisateur par email" 
                name="search-user"
                wire:model.live.debounce.500ms="search"
                autofocus
            >
                <x-slot:prefix>
                    <x-lucide-search wire:loading.remove wire:target="search" class="size-4 text-neutral-500"/>
                    <x-ui.spinner wire:loading wire:target="search" class="size-4" />
                </x-slot:prefix>
            </x-ui.input>
            <x-ui.button variant="primary" size="md">
                Envoyer
            </x-ui.button>
        </div>

        <!-- Invited Users List -->
        <div class="mt-6 overflow-x-auto flex-1">
            {{-- <h3 class="font-medium mb-4">Invitations en attente</h3> --}}
            <div class="space-y-3">
                <!-- Example of an invited user -->
                @forelse ($this->users as $user)
                    <x-ui.invited-user class="mr-2.5" :user="$user" />
                @empty
                    <div>
                        <p class="text-sm text-neutral-500 text-center">Aucun utilisateur trouvé</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Spacer -->
    <div class="bg-neutral-100 rounded-lg flex-1 p-2">
        <div>
            <h2 class="font-medium">Invités actuels</h2>
            <p class="text-neutral-400 text-sm">Consultez et gérez les membres déjà ajoutés au tableau.</p>
        </div>
    </div>
</div>