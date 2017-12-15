@component('mail::message')
# Je inschrijving voor de mensa op {{ formatDate($mensaUser->mensa->date, false, false) }} is hierbij bevestigd

Beste {{ $mensaUser->user->name }},

Je ingeschrijving voor de mensa op {{ formatDate($mensaUser->mensa->date, false, false) }} is hierbij bevestigd.
Wil je je uitschrijven? <a href="{{ route('signin.cancel', [$mensaUser->confirmation_code]) }}">Klik dan hier</a>.

Wil je je inschrijving wijzigen, <a href="{{ route('signin.edit', [$mensaUser->confirmation_code]) }}">klik dan hier</a>.

Houd er rekening mee dat je na sluitingstijd van deze mensa (om {{ $mensaUser->mensa->closingTime() }}) geen wijzigingen meer kunt maken en je niet meer kan uitschrijven.

@component('mail::table')
| Opties         | Prijs         |
| :------------ | :------------ |
| Standaard     | &euro;{{ number_format($mensaUser->mensa->price, 2) }} |
@foreach($mensaUser->extraOptions as $extraOption)
| {{ $extraOption->description }} | &euro;{{ number_format($extraOption->price, 2) }} |
@endforeach
| &nbsp;<br />Met voor jou een totaal bedrag van: | &nbsp;<br />&euro;{{ number_format($mensaUser->price(), 2) }} |
@endcomponent

@if($mensaUser->intros()->count())
{{ ($mensaIntro = $mensaUser->intros[0]) ? '':'' }}
@component('mail::table')
| Opties (voor je intro) | Prijs         |
| :------------ | :------------ |
| Standaard     | &euro;{{ number_format($mensaIntro->mensa->price, 2) }} |
@foreach($mensaIntro->extraOptions as $extraOption)
| {{ $extraOption->description }} | &euro;{{ number_format($extraOption->price, 2) }} |
@endforeach
| &nbsp;<br />Met voor je intro een totaal bedrag van: | &nbsp;<br />&euro;{{ number_format($mensaIntro->price(), 2) }} |
@endcomponent
@endif

We zien je dan!
<br /><br />
Met vriendelijke groet,<br />
De mensacomputer

Vragen of problemen? Bel de bar: <a href="tel://{{ str_replace('-', '', config('mensa.contact.bar')) }}">{{ config('mensa.contact.bar') }}</a>
@endcomponent


