@component('mail::message')
    # Mededeling van prijsverandering van de mensa op {{ formatDate($mensaUser->mensa->date, false, false) }}

    Beste {{ $mensaUser->user->name }},

    Hierbij willen we je laten weten dat de {{ ($mensaUser->mensa->extraOptions()->count() < 1) ? 'prijs':'prijzen' }} van de mensa op {{ formatDate($mensaUser->mensa->date, false, false) }} zijn veranderd.
    <br/><br/>
    Met vriendelijke groet,<br/>
    De mensacomputer

    Vragen of problemen? Bel de bar: <a
            href="tel://{{ str_replace('-', '', config('mensa.contact.bar')) }}">{{ config('mensa.contact.bar') }}</a>
@endcomponent
