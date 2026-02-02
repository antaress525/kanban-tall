<?php

namespace App\Http\Controllers\Auth;

use App\Models\EmailChange;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ConfirmEmailChangeController extends Controller
{
    public function __invoke(Request $request, string $token)
    {
        $emailChange = EmailChange::where('token', $token)
            ->where('expires_at', '>', now())
            ->firstOrFail();

        $user = $emailChange->user;

        $user->update([
            'email' => $emailChange->new_email,
            'email_verified_at' => now(),
        ]);

        $emailChange->delete();

        return redirect()
            ->route('setting.account')
            ->with('success', 'Email modifié avec succès');

    }
}
