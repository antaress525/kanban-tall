<p>Bonjour,</p>

<p>
    Clique sur le lien ci-dessous pour confirmer ton nouvel email :
</p>

<p>
    <a href="{{ route('email.change.confirm', $emailChange->token) }}">
        Confirmer mon email
    </a>
</p>

<p>
    Ce lien expire dans 30 minutes.
</p>
<p>
    Si tu n'as pas demandé ce changement, ignore simplement cet email.
</p>