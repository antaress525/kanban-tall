<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AcceptBoardInvitationController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, string $token)
    {
        $invitation = Invitation::where('token', $token)
            ->where('status', '=',  'pending')
            ->first();
        
        if(!$invitation) {
            abort(404, 'Invitation invalide ou déjà utilisée.');
        }

        if($invitation->expires_at->isPast()) {
            abort(403, 'Invitation expirée.');
        }

        if(!auth()->check()) {
            session()->put('invitation_token', $token);

            return redirect()->route('auth.login');
        }

        $user = auth()->user();

        if($user->email !== $invitation->email) {
            abort(403, 'Cette invitation ne vous est pas destinée.');
        }
        DB::transaction(function () use ($invitation, $user) {

            $alreadyMember = $invitation->board
                ->members()
                ->where('user_id', $user->id)
                ->exists();
            if (!$alreadyMember) {
                $invitation->board->members()->attach($user->id);
            }

            $invitation->update([
                'status' => 'accepted',
                'accepted_at' => now(),
            ]);
        });

        return to_route('board.show', $invitation->board)
            ->with('success', 'Vous avez rejoint le tableau avec succès.');
    }
}
