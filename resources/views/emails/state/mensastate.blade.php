<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Mensastaat</title>
</head>
<body>
<b>Mensaoverzicht van {{ formatDate($mensa->date, false, false) }}</b><br />
Gegenereerd op {{ \Carbon\Carbon::now() }}
<hr style="border: none; border-bottom: 1px solid black;"/>
<table style="border-spacing: 10px 0; width: 100%;">
    <tr style="vertical-align: top;">
        <td>
            <table style="border-spacing: 0px 0px; white-space: nowrap;">
                <tr>
                    <td>Gasten</td>
                    <td></td>
                    <td style="text-align: right; padding-left: 5px; padding-right: 10px;">{{ $mensa->payingUsers() }}</td>
                    <td>{{ $secondDishwasher ? '*':'' }}</td>
                </tr>
                <tr>
                    <td>Eters</td>
                    <td></td>
                    <td style="text-align: right; padding-left: 5px; padding-right: 10px;">{{ $mensa->users()->count() }}</td>
                    <td>{{ $secondDishwasher ? '*':'' }}</td>
                </tr>
                <tr>
                    <td>Vegetari&euml;rs</td>
                    <td></td>
                    <td style="text-align: right; padding-left: 5px; padding-right: 10px;">{{ $mensa->users()->where('vegetarian', '1')->count() }}</td>
                    <td>{{ $secondDishwasher ? '*':'' }}</td>
                </tr>
                <tr>
                    <td>Budget</td>
                    <td>&euro;</td>
                    <td style="text-align: right; padding-left: 5px; padding-right: 10px;">{{ number_format($mensa->budget(), 2) }}</td>
                    <td>{{ $secondDishwasher ? '*':'' }}</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td>Inkomsten&nbsp;&nbsp;&nbsp;</td>
                    <td>&euro;</td>
                    <td style="text-align: right; padding-left: 5px; padding-right: 10px;">{{ number_format($mensa->budget(true), 2) }}</td>
                    <td>{{ $secondDishwasher ? '*':'' }}</td>
                </tr>
                <tr>
                    <td>Afwasser</td>
                    <td style="border-bottom: 1px solid black;">&euro;</td>
                    <td style="border-bottom: 1px solid black; text-align: right; padding-left: 5px; padding-right: 10px;">{{ number_format($mensa->payingUsers() * env('MENSA_SUBTRACT_DISHWASHER'), 2) }}</td>
                    <td>-{{ $secondDishwasher ? '*':'' }}</td>
                </tr>
                <tr>
                    <td>Subtotaal</td>
                    <td>&euro;</td>
                    <td style="text-align: right; padding-left: 5px; padding-right: 10px;">{{ number_format($mensa->budget(true) - ($mensa->payingUsers() * env('MENSA_SUBTRACT_DISHWASHER')), 2) }}</td>
                    <td>{{ $secondDishwasher ? '*':'' }}</td>
                </tr>
                <tr>
                    <td>Uitgaven</td>
                    <td style="border-bottom: 1px solid black;">&euro;</td>
                    <td style="border-bottom: 1px solid black; text-align: right; padding-left: 5px; padding-right: 10px;"></td>
                    <td>-</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td></td>
                    <td style="text-align: right; padding-left: 5px; padding-right: 10px;"></td>
                    <td></td>
                </tr>
                <tr>
                    <td>Totaal</td>
                    <td style="border-bottom: 1px solid black;">&euro;</td>
                    <td style="border-bottom: 1px solid black; text-align: right; padding-left: 5px; padding-right: 10px;"></td>
                    <td></td>
                </tr>
            </table>
            <br />
            Keukencheck door:
            <br /><br /><br />
            <hr style="border: none; border-bottom: 1px solid black;" />
            Handtekening:
            <br /><br /><br />
            <hr style="border: none; border-bottom: 1px solid black;" />
            <br />
            @foreach($personel as $user)
                @component('emails.state.consumptiontable', ['consumptions' => $user->consumptions()])
                    {{ $user->user->name }}
                @endcomponent
            @endforeach
            @if($cooks < 1)
                @component('emails.state.consumptiontable', ['consumptions' => $mensa->consumptions(true, false)])
                    Koker 1
                @endcomponent
            @endif
            @if($dishwashers < 1)
                @component('emails.state.consumptiontable', ['consumptions' => $mensa->consumptions(false, true)])
                    Afwasser 1
                @endcomponent
            @endif
            @if($secondDishwasher)
                @component('emails.state.consumptiontable', ['consumptions' => $mensa->consumptions(false, true)])
                    Afwasser 2
                @endcomponent
            @endif
        </td>
        <td style="width:100%;">
            <table style="border-spacing: 0px 0px; border-collapse: collapse; width: 100%;">
                <tr>
                    <th style="border: 1px solid black; text-align: left; padding-left: 5px;" colspan="3">Personeel</th>
                </tr>
                <tr>
                    <th style="border: 1px solid black; text-align: left; padding-left: 5px; padding-right: 5px;">Naam</th>
                    <th style="border: 1px solid black; text-align: left; padding-left: 5px; padding-right: 5px; width: 100%;">Opmerkingen</th>
                    <th style="border: 1px solid black; text-align: left; padding-left: 5px; padding-right: 5px;">Functie</th>
                </tr>
                @foreach($personel as $user)
                    @component('emails.state.userrow', ['index' => $personelIndex++, 'user' => $user])
                    @endcomponent
                @endforeach
                @if($cooks < 1)
                    @component('emails.state.userrow', ['index' => $personelIndex++, 'extra' => 'Koker']) @endcomponent
                @endif
                @if($dishwashers < 1)
                    @component('emails.state.userrow', ['index' => $personelIndex++, 'extra' => 'Afwasser']) @endcomponent
                @endif
                @if($secondDishwasher)
                    @component('emails.state.userrow', ['index' => $personelIndex++, 'extra' => 'Afwasser']) @endcomponent
                @endif
                <tr>
                    <th style="border: 1px solid black; text-align: left; padding-left: 5px; padding-right: 5px;" colspan="3">Gasten</th>
                </tr>
                <tr>
                    <th style="border: 1px solid black; text-align: left; padding-left: 5px; padding-right: 5px;">Naam</th>
                    <th style="border: 1px solid black; text-align: left; padding-left: 5px; padding-right: 5px; width: 100%">Opmerkingen</th>
                    <th style="border: 1px solid black; text-align: left; padding-left: 5px; padding-right: 5px;">Betaald</th>
                </tr>
                @foreach($guests as $user)
                    @component('emails.state.userrow', ['index' => $loop->iteration, 'user' => $user]) @endcomponent
                @endforeach
            </table>
            @if($secondDishwasher)
                <br />
                *LET OP: wanneer er geen 2de afwasser volgt, stijgt het aantal gasten met 1,
                gaat het budget naar &euro;{{ number_format($mensa->budget()+$mensa->defaultBudgetPerPayingUser(), 2) }},
                inkomsten naar &euro;{{ number_format($mensa->budget(true) + $mensa->defaultBudgetPerPayingUser(), 2) }},
                afwasser naar &euro;{{ number_format(($mensa->payingUsers()+1) * env('MENSA_SUBTRACT_DISHWASHER'), 2) }}
                en het subtotaal wordt &euro;{{ number_format( ($mensa->budget(true) + $mensa->defaultBudgetPerPayingUser()) - (($mensa->payingUsers()+1) * env('MENSA_SUBTRACT_DISHWASHER'))  , 2) }}.

                @if($singleDishwasherExtraConsumptions > 0)
                    <br /><br />
                    Ook krijgt de afwasser {{ $singleDishwasherExtraConsumptions }} extra consumptie{{ $singleDishwasherExtraConsumptions==1?'':'s' }}.
                @endif
            @endif
        </td>
    </tr>
</table>
</body>
</html>