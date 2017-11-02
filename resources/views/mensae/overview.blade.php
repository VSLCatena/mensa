@extends('mensae.base')

@section('overview.content')
    <div class="alert alert-success">
        Er {{ ($users!=1)?'zijn':'is' }} in totaal {{ $users }} eter{{ ($users!=1)?'s':'' }}
        waarvan {{ $intros }} introduc&eacute;{{ ($intros != 1)?'s':'' }}.
        <br />
        @if($dishwashers > 1 || $users < 15)
            Het budget bedraagt &euro;{{ number_format($budget, 2) }}.
        @else
            Het budget bedraagt &euro;{{ number_format($budget-($mensa->price-0.3), 2) }}.
            Wanneer er geen tweede afwasser gekozen wordt,
            is het budget {{ number_format($budget, 2) }}
        @endif
        @if($mensa->extraOptions->count() > 0)
            <br /><br />
            <i>De hieronder genoemde prijzen zijn slechts een <u>indicatie</u> om je een idee te geven wat je budget is per optie!</i>
            @foreach($mensa->extraOptions as $extraOption)
                <br />
                Extra optie <i>{{ $extraOption->description }}</i>
                is {{ $extraOption->users()->count() }}x gekozen
                (budget van &euro;{{ number_format($extraOption->users()->where('cooks', '0')->where('dishwasher', '0')->count() * $extraOption->price, 2) }})
            @endforeach
            <br />
            Dan is er nog &euro;{{ number_format($mensa->defaultBudget(), 2) }} over voor de rest.
        @endif
    </div>
    <div class="alert alert-info">
        @if($cooks != 1)
            Er zijn {{ $cooks }} kokers.
        @else
            Er is {{ $cooks }} koker.
        @endif
        Huidig maximum: {{ ($users < 15) ? 1 : 2 }}
    </div>
    <div class="alert alert-info">
        @if($dishwashers != 1)
            Er zijn {{ $dishwashers }} afwassers.
        @else
            Er is {{ $dishwashers }} afwasser.
        @endif
        Huidig maximum: {{ ($users < 15) ? 1 : 2 }}
    </div>
@endsection
