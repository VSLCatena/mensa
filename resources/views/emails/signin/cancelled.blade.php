@component('mail::message')
# Uitschrijving voor de mensa op {{ formatDate($mensaUser->mensa->date, false, false) }}

Beste {{ $mensaUser->user->name }},

Je hebt je succesvol uitgeschreven voor de mensa op {{ formatDate($mensaUser->mensa->date, false, false) }}.
<br /><br />
Met vriendelijke groet,<br />
De mensacomputer

Vragen of problemen? Bel de bar: <a href="tel://0715120774">071-5120774</a>
@endcomponent


