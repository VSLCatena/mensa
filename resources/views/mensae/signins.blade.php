@extends('mensae.base')

@section('styles')
    @parent
    <link rel="stylesheet" href="{{ asset('css/signins.css') }}" />
@endsection

@section('overview.content')
    <table class="table table-signins table-striped responsive-table">
        <thead>
            <tr>
                <th>Bevestigd</th>
                <th>Naam</th>
                <th>Allergie&euml;n & extra info</th>
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
                        {{ $mUser->name }}<br />
                        {{ $mUser->phonenumber }}
                    </td>
                    <td>
                        <div class="labels">
                            @if($mUser->vegetarian)
                                &nbsp;<span class="label label-success label-yesno">Vego</span>
                            @endif
                            @if($mUser->cooks)
                                &nbsp;<span class="label label-primary label-yesno">Koker</span>
                            @endif
                            @if($mUser->dishwasher)
                                &nbsp;<span class="label label-primary label-yesno">Afwasser</span>
                            @endif
                        </div>
                        @if(!empty($mUser->allergies))
                            Allergie&euml;n: {{ $mUser->allergies }}<br />
                        @endif
                        @if(!empty($mUser->extra_info))
                            Extra info: {{ $mUser->extra_info }}
                        @endif
                        &nbsp;
                    </td>
                    @if($mUser->isStaff())
                        <td></td>
                    @elseif($mUser->price() == $mUser->paid)
                        <td><button data-id="{{ $mUser->id }}" class="btn btn-success btn-paid {{ $mensa->closed?'disabled':'' }}">&euro;{{ number_format($mUser->price(), 2) }}</button></td>
                    @else
                        <td><button data-id="{{ $mUser->id }}" class="btn btn-{{ ($mUser->price() < $mUser->paid)?'warning':'danger' }} btn-paid {{ $mensa->closed?'disabled':'' }}">&euro;{{ number_format($mUser->price() - $mUser->paid, 2) }}</button></td>
                    @endif
                    <td>{{ $mUser->created_at }}</td>
                    <td>
                        @if(!$mensa->closed)
                            <div class="btn-group-vertical">
                                <a href="{{ route('mensa.editsignin', ['mensaId' => $mUser->mensa_id, 'userId' => $mUser->id]) }}" class="btn btn-xs btn-default">Wijzigen</a>
                                <a href="{{ route('mensa.removesignin', ['mensaId' => $mUser->mensa_id, 'userId' => $mUser->id]) }}" class="btn btn-xs btn-default">Uitschrijven</a>
                            </div>
                        @else
                            <div class="btn-group-vertical">
                                <span class="btn btn-xs btn-default disabled">Wijzigen</span>
                                <span class="btn btn-xs btn-default disabled">Uitschrijven</span>
                            </div>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @if(!$mensa->closed)
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
                btn.removeClass('btn-success btn-danger btn-warning');
                btn.blur();

                $.post("{{ route('mensa.togglepaid', ['id' => $mensa->id]) }}", {id: $(this).data('id')}, function (data) {
                    if(data.error !== undefined){
                        btn.addClass(hasPaid ? 'btn-success' : 'btn-danger');
                        alert("Whoops! Er ging iets verkeerd bij het aanpassen van het betalen! :(")
                    } else {
                        btn.removeClass('btn-default');
                        btn.html(data.price);
                        btn.addClass(data.paid ? 'btn-success' : 'btn-danger');
                    }
                }).fail(function(){
                    btn.addClass(hasPaid ? 'btn-success' : 'btn-danger');
                    alert("Whoops! Er ging iets verkeerd bij het aanpassen van het betalen! :(")
                });
            });
        </script>
    @endif
@endsection
