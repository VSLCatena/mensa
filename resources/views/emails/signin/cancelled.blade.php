@component('mail::message')
    # Uitschrijving voor de mensa op {{ formatDate($mensaUser->mensa->date, false, false) }}

    Beste {{ $mensaUser->user->name }},

    Je hebt je succesvol uitgeschreven voor de mensa op {{ formatDate($mensaUser->mensa->date, false, false) }}.
    <br/><br/>
    Met vriendelijke groet,<br/>
    De mensacomputer

    Vragen of problemen? Bel de bar: <a
            href="tel://{{ str_replace('-', '', config('mensa.contact.bar')) }}">{{ config('mensa.contact.bar') }}</a>
@endcomponent


