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
| Standaard     | &euro;{{ number_format(!$mensaUser->isStaff()?$mensaUser->mensa->price:0, 2) }} |
@foreach($mensaUser->extraOptions as $extraOption)
| {{ $extraOption->description }} | &euro;{{ number_format(!$mensaUser->isStaff()?$extraOption->price:0, 2) }} |
@endforeach
| &nbsp;<br />Met voor jou een totaal bedrag van: | &nbsp;<br />&euro;{{ number_format(!$mensaUser->isStaff()?$mensaUser->price():0, 2) }} |
@endcomponent
@if($mensaUser->isStaff())
<i>Je bent ingeschreven als {{ $mensaUser->cooks?"koker":"" }} {{ $mensaUser->dishwasher?($mensaUser->cooks?"en als ":"")."afwasser":"" }} en hoeft daarom niet te betalen!</i>
<br />
@endif

@if(!empty($mensaUser->allergies) || !empty($mensaUser->extra_info))
<u>Extra opgegeven info:</u>
@endif
@if(!empty($mensaUser->allergies))
<br />Allergieën: <i>{{ $mensaUser->allergies }}</i>
@endif
@if(!empty($mensaUser->extra_info))
<br />Extra info: <i>{{ $mensaUser->extra_info }}</i>
@endif

@if($mensaUser->intros()->count())
<br />{{ ($mensaIntro = $mensaUser->intros[0]) ? '':'' }}
@component('mail::table')
| Opties (voor je intro) | Prijs         |
| :------------ | :------------ |
| Standaard     | &euro;{{ number_format(!$mensaIntro->isStaff()?$mensaIntro->mensa->price:0, 2) }} |
@foreach($mensaIntro->extraOptions as $extraOption)
| {{ $extraOption->description }} | &euro;{{ number_format(!$mensaIntro->isStaff()?$extraOption->price:0, 2) }} |
@endforeach
| &nbsp;<br />Met voor je intro een totaal bedrag van: | &nbsp;<br />&euro;{{ number_format(!$mensaIntro->isStaff()?$mensaIntro->price():0, 2) }} |
@endcomponent
@if($mensaIntro->isStaff())
    <i>Je bent ingeschreven als {{ $mensaIntro->cooks?"koker":"" }} {{ $mensaIntro->dishwasher?($mensaIntro->cooks?"en als ":"")."afwasser":"" }} en hoeft daarom niet te betalen!</i>
    <br />
@endif

@if(!empty($mensaIntro->allergies) || !empty($mensaIntro->extra_info))
<u>Extra opgegeven info voor je intro:</u>
@endif
@if(!empty($mensaIntro->allergies))
<br />Allergieën: <i>{{ $mensaIntro->allergies }}</i>
@endif
@if(!empty($mensaIntro->extra_info))
<br />Extra info: <i>{{ $mensaIntro->extra_info }}</i>
@endif
<br /><br />
@endif
<br />
We zien je dan!
<br /><br />
Met vriendelijke groet,<br />
De mensacomputer

Vragen of problemen? Bel de bar: <a href="tel://{{ str_replace('-', '', config('mensa.contact.bar')) }}">{{ config('mensa.contact.bar') }}</a>
@endcomponent


