@extends('mensae.base')

@section('overview.content')
    <table class="table table-signins">
        <thead>
            <tr>
                <th>Bevestigd</th>
                <th>Naam</th>
                <th>Allergie&euml;en/vegetarisch</th>
                <th>Koker</th>
                <th>Afwasser</th>
                <th>Betaald</th>
                <th>Inschrijftijd</th>
                <th>Acties</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $mUser)
                <tr>
                    <td>
                        @if($mUser->confirmed) <span class="label label-success label-yesno">Ja</span>
                        @else <span class="label label-default label-yesno">Nee</span>
                        @endif
                    </td>
                    <td>
                        @if($mUser->is_intro)Intro van @endif
                        {{ $mUser->user->name }}<br />
                        {{ $mUser->user->phonenumber }}
                    </td>
                    <td>
                        {{ $mUser->allergies }}
                        @if(!empty($mUser->allergies))<br />@endif
                        {{ $mUser->wishes }}
                    </td>
                    <td>
                        @if($mUser->cooks) <span class="label label-success label-yesno">Ja</span>
                        @else <span class="label label-default label-yesno">Nee</span>
                        @endif
                    </td>
                    <td>
                        @if($mUser->dishwasher) <span class="label label-success label-yesno">Ja</span>
                        @else <span class="label label-default label-yesno">Nee</span>
                        @endif
                    </td>
                    <td>
                        @if($mUser->cooks || $mUser->dishwasher)
                        @elseif($mUser->paid)
                            <button data-id="{{ $mUser->id }}" class="btn btn-success btn-paid">&euro;{{ number_format($mUser->price(), 2) }}</button>
                        @else
                            <button data-id="{{ $mUser->id }}" class="btn btn-danger btn-paid">&euro;{{ number_format($mUser->price(), 2) }}</button>
                        @endif
                    </td>
                    <td>{{ $mUser->created_at }}</td>
                    <td>
                        <div class="btn-group-vertical">
                            <button class="btn btn-xs btn-default">Wijzigen</button>
                            <button class="btn btn-xs btn-default">Uitschrijven</button>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });

        $(".btn-paid").click(function(){
            console.debug($(this));
            var btn = $(this);
            var hasPaid = btn.hasClass('btn-success');
            btn.removeClass('btn-success btn-danger');
            btn.blur();

            $.post("{{ route('mensa.togglepaid') }}", {id: $(this).data('id')}, function (data) {
                if(data.error !== undefined){
                    btn.addClass(hasPaid ? 'btn-success' : 'btn-danger');
                    alert("Whoops! Er ging iets verkeerd bij het aanpassen van het betalen! :(")
                } else {
                    btn.removeClass('btn-default');
                    btn.addClass(data.paid ? 'btn-success' : 'btn-danger');
                }
            }).fail(function(){
                btn.addClass(hasPaid ? 'btn-success' : 'btn-danger');
                alert("Whoops! Er ging iets verkeerd bij het aanpassen van het betalen! :(")
            });
        });
    </script>
@endsection
