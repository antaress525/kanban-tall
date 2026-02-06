<p>vous aviez été invité à rejoindre le tableau "{{ $invitation->board->name }}"</p>
<p>
    Cliquez sur le lien ci-dessous pour accepter l'invitation :
</p>
<p>
    <a href="{{ route('board.invitations.accept', $invitation->token) }}">
        Accepter l'invitation
    </a>
</p>