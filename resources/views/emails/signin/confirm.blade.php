@component('mail::message')
    # Bevestig je inschrijving voor de mensa op {{ formatDate($mensaUser->mensa->date, false, false) }}

    Beste {{ $mensaUser->user->name }},

    Je hebt je ingeschreven voor de mensa op {{ formatDate($mensaUser->mensa->date, false, false) }} maar deze moet nog wel bevestigd worden.
    <br/>
    <a href="{{ route('signin.confirm', [$mensaUser->confirmation_code]) }}">Bevestig je inschrijving</a>

    Je wordt verzocht om {{ formatTime($mensaUser->mensa->date) }} aanwezig te zijn. Deze mensa kost &euro;{{ number_format($mensaUser->price(), 2) }}.
    <br/>
    Er is momenteel nog {{ count($mensaUser->mensa->dishwashers()) > 0 ? 'een':'geen' }} afwasser geregeld.

    <b>LET OP</b>: Wanneer je jouw inschrijving niet binnen 15 minuten bevestigt, vervalt je inschrijving.
    <br/><br/>
    Met vriendelijke groet,<br/>
    De mensacomputer

    Vragen of problemen? Bel de bar: <a
            href="tel://{{ str_replace('-', '', config('mensa.contact.bar')) }}">{{ config('mensa.contact.bar') }}</a>
@endcomponent


