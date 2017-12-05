@extends('mensae.base')

@section('overview.content')
    <div class="alert alert-{{ $payingUsers >= env('MENSA_MINIMUM_PAYING_SIGNINS', 6) ? 'success':'danger' }}">
        Er {{ ($users!=1)?'zijn':'is' }} in totaal {{ $users }} eter{{ ($users!=1)?'s':'' }}
        waarvan {{ $intros }} introduc&eacute;{{ ($intros != 1)?'s':'' }}.<br />
        {{ $vegetarians }} hiervan {{ ($vegetarians == 1)?'is':'zijn' }} vegetarisch.

        <br />
        @if($payingUsers < env('MENSA_MINIMUM_PAYING_SIGNINS', 6))
            Minimaal {{ env('MENSA_MINIMUM_PAYING_SIGNINS', 6) }} (betalende) gasten zijn nodig om de mensa mogelijk te maken. Op dit moment {{ $payingUsers==1?'is':'zijn' }} het er {{ $payingUsers }}.
        @elseif($dishwashers > 1 || $payingUsers < env('MENSA_SECOND_DISHWASHER', 15))
            Het budget bedraagt &euro;{{ number_format($budget, 2) }}.
        @else
            Het budget bedraagt &euro;{{ number_format($budget, 2) }}.
            Wanneer er geen tweede afwasser gekozen wordt,
            is het budget &euro;{{ number_format($budget + $mensa->defaultBudgetPerPayingUser(), 2) }}
        @endif


        @if($mensa->extraOptions->count() > 0 && $payingUsers >= env('MENSA_MINIMUM_PAYING_SIGNINS', 6))
            <br /><br />
            <i>De hieronder genoemde prijzen zijn slechts een <u>indicatie</u> om je een idee te geven wat je budget is per optie!</i>
            @foreach($mensa->extraOptions as $extraOption)
                <br />
                Extra optie <i>{{ $extraOption->description }}</i>
                is {{ $extraOption->users()->count() }}x gekozen
                (budget van &euro;{{ number_format($extraOption->users()->whereNotIn('id', $staffIds)->count() * $extraOption->price, 2) }})
            @endforeach
            <br />
            @if($dishwashers > 1 || $payingUsers < env('MENSA_SECOND_DISHWASHER', 15))
                Dan is er nog &euro;{{ number_format($mensa->defaultBudget(), 2) }} over voor de rest.
            @else
                Dan is er nog &euro;{{ number_format($mensa->defaultBudget() - ($mensa->price - env('MENSA_SUBTRACT_KITCHEN')), 2) }} over voor de rest.
                <i><small>(mits er geen tweede afwasser wordt gekozen)</small></i>
            @endif
        @endif

    </div>
    <div class="alert alert-{{ $cooks ? 'success' : 'danger' }}">
        @if($cooks != 1)
            Er zijn {{ $cooks }} kokers.
        @else
            Er is {{ $cooks }} koker.
        @endif
        Huidig maximum: {{ ($payingUsers < env('MENSA_SECOND_COOK', 15)) ? 1 : 2 }}
    </div>


    <div class="alert alert-{{ ($dishwashers < $mensa->maxDishwashers())?'warning':'success' }}">
        @if($dishwashers != 1)
            Er zijn {{ $dishwashers }} afwassers.
        @else
            Er is {{ $dishwashers }} afwasser.
        @endif
        Huidig maximum: {{ ($payingUsers < env('MENSA_SECOND_DISHWASHER', 15)) ? 1 : 2 }}
    </div>
@endsection
