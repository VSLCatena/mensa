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
