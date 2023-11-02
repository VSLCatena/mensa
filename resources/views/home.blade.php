@extends('base')

@section('styles')
    @parent
    <link rel="stylesheet" href="{{ asset('css/home.css') }}" />
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        @if(!isset($page) || $page == 0)
                            Mensae in de komende 2 weken
                        @else
                            Mensae tussen {{ formatDate(\Carbon\Carbon::today()->addWeeks($page*2), false, false, false) }}
                            en {{ formatDate(\Carbon\Carbon::today()->addWeeks($page*2 + 2), false, false, false) }}
                        @endif
                    </div>

                    <div class="panel-body">
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif
                        <table class="table responsive-table mensae">
                            <thead>
                            <tr>
                                <th>Datum</th>
                                <th>Beschrijving</th>
                                <th>Prijs</th>
                                <th>Inschrijvingen</th>
                                <th>Sluittijd</th>
                                <th>Inschrijven</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($mensae as $mensa)
                                @if($mensa->max_users <= 0 && $mensa->users()->count() <= 0 && (!Auth::check() || !Auth::user()->mensa_admin))
                                    @continue;
                                @endif
                                <tr @if($mensa->max_users <= 0 && $mensa->users()->count() <= 0)class="cancelled" @endif>
                                    <td>{{ formatDate($mensa->date, true) }}</td>
                                    <td>
                                        {{ $mensa->title }}
                                        @if($mensa->menuItems()->count() > 0)
                                            <small class="menu-toggler text-nowrap"><a href="#">(Klik voor het menu)</a></small>
                                            <div class="menu-toggle">
                                                <h4>Menu:</h4>
                                                <ul>
                                                    @foreach($mensa->menuItems as $menuItem)
                                                        <li>{{ $menuItem->text }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif
                                        @foreach($mensa->extraOptions as $option)
                                            <div><strong><u>Extra optie:</u></strong> {{ $option->description }} voor &euro;{{ $option->price }}</div>
                                        @endforeach
                                        @if(strlen($mensa->cooksFormatted()) > 0)
                                            <div>Gekookt door: {{ $mensa->cooksFormatted() }}</div>
                                        @endif
                                    </td>
                                    <td>&euro;{{ $mensa->price }}</td>
                                    <td>
                                        {{ $mensa->users()->count() }}/{{ $mensa->max_users }}<br />
                                        @if(count($mensa->dishwashers()) > 0)
                                            {{ count($mensa->dishwashers()) }} afwasser{{ ((count($mensa->dishwashers()) > 1)?'s':'') }}*
                                        @endif
                                    </td>
                                    <td>
                                        @if($mensa->max_users <= 0)
                                            <span class="text-danger">Geannuleerd</span>
                                        @elseif(!$mensa->isClosed())
                                            {{ $mensa->closingTime(true) }}
                                        @else
                                            <span class="text-danger">Gesloten</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group-vertical">
                                            @if($mensa->max_users <= 0 && (!Auth::check() || !Auth::user()->service_user))
                                                <span class="btn btn-primary disabled">Geannuleerd</span>
                                            @elseif(!Auth::check() || !Auth::user()->service_user)
                                                @if(Auth::check() && $mensa->users->where('lidnummer', Auth::user()->lidnummer)->count() > 0)
                                                    <form method="POST" @can('softEdit', $mensa) class="btn-group-vertical" @endcan action="{{ route('signout', ['id' => $mensa->id]) }}">
                                                        <input type="submit" class="btn btn-danger {{ $mensa->isClosed()?'disabled':'' }}" value="Uitschrijven" {{ $mensa->closed?'disabled':'' }} />
                                                        {{ csrf_field() }}
                                                        <input type="hidden" name="id" value="{{ $mensa->id }}" />
                                                    </form>
                                                @elseif(!$mensa->isClosed())
                                                    @if($mensa->max_users > $mensa->users()->count())
                                                        <a href="{{ route('signin', ['mensaId' => $mensa->id]) }}" class="btn btn-primary">Inschrijven</a>
                                                    @else
                                                        <span class="btn btn-primary disabled">Vol</span>
                                                    @endif
                                                @else
                                                    <span class="btn btn-primary disabled">Gesloten</span>
                                                @endif
                                            @endif
                                            @can('softEdit', $mensa)
                                            <a href="{{ route('mensa.overview', ['mensaId' => $mensa->id]) }}" class="btn btn-primary">Bekijken</a>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">Geen mensas gevonden!</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="panel-footer">
                        <a href="{{
                        $page == 1 ?
                            route('home') :
                            route('home.page', ['page' => (isset($page) ? $page-1 : -1)])
                    }}" class="pull-left">&lt;&lt;</a>

                        <a href="{{
                        $page == -1 ?
                            route('home') :
                            route('home.page', ['page' => (isset($page) ? $page+1 : 1)])
                    }}" class="pull-right">&gt;&gt;</a>
                        <br class="clearfix"/>
                    </div>
                </div>
            </div>
        </div>
        <script type="text/javascript">
            $(".menu-toggler").click(function(){
                $(this).parent().find(".menu-toggle").toggle(400);
            });
        </script>
    </div>
@endsection
