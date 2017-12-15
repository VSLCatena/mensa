@component('mail::message')
# Mededeling van het annuleren van de mensa op {{ formatDate($mensaUser->mensa->date, false, false) }}

Beste {{ $mensaUser->user->name }},

Hierbij willen we je laten weten dat de mensa op {{ formatDate($mensaUser->mensa->date, false, false) }} is geannuleerd.<br />
Al heb je al betaald dan kan je je geld terug vragen bij de bar.
<br /><br />
Met vriendelijke groet,<br />
De mensacomputer

Vragen of problemen? Bel de bar: <a href="tel://{{ str_replace('-', '', config('mensa.contact.bar')) }}">{{ config('mensa.contact.bar') }}</a>
@endcomponent
