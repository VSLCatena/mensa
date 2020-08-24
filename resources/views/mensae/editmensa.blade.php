@extends('base')

@section('styles')
    @parent
    <link href="{{ asset('css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet">
@endsection

@section('scripts')
    @parent
    <script src="{{ asset('js/moment.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap-datetimepicker.min.js') }}"></script>
    <script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular.min.js"></script>
    <script src="{{ asset('js/sortable.min.js') }}"></script>
@endsection

@section('content')
    <div class="container" ng-app="mensa">
        <div class="row">
            <div class="col-xs-12 col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">{{ $mensa->id == null ? 'Mensa aanmaken' : ('Mensa wijzigen van '.formatDate($mensa->date, false, false)) }}</div>
                    <div class="panel-body">
                        @if($mensa->id != null && Auth::user()->mensa_admin)
                            <div class="alert alert-info">
                                <strong>Note:</strong> Bij het aanpassen van de prijs wordt iedereen die zich heeft ingeschreven op de hoogte gebracht.
                            </div>
                        @endif
                        <form method="POST" action="">
                            {{ csrf_field() }}
                            <input type="hidden" name="edited" value="true" />
                            <div class="form-group">
                                <label for="title">Titel:</label>
                                <input id="title" name="title" value="{{ old('title', $mensa->title) }}" class="form-control"  />
                                <h6>Prefix je titel met r| of m| voor de label [r| Reservering] of [m| Mensa]</h6>
                                <h6>Bijvoorbeeld: "m|Mensa met betaalde afwas"</h6>
                                @if ($errors->has('title'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('title') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="date">Datum:</label>
                                <div class="input-group date" id="datepicker">
                                    <input id="date" name="date" class="form-control" @notadmin readonly @endnotadmin />
                                    <label for="date" class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </label>
                                </div>

                                @if ($errors->has('date'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('date') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="closing_time">Sluittijd:</label>
                                <div class="input-group date">
                                    <input id="closing_time" name="closing_time" class="form-control" @notadmin readonly @endnotadmin />
                                    <label for="closing_time" class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </label>
                                </div>

                                @if ($errors->has('closing_time'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('closing_time') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="max_users">Max inschrijvingen:</label>
                                <input id="max_users" type="number" name="max_users" value="{{ old('max_users', $mensa->max_users) }}" class="form-control" @notadmin readonly @endnotadmin />

                                @if ($errors->has('max_users'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('max_users') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <fieldset ng-controller="MenuEditor as mensaMenu">
                                <legend>Menu:</legend>
                                <div ui-sortable ng-model="mensaMenu.items">
                                    <div class="row price-options sortable" ng-repeat="item in mensaMenu.items">
                                        <input type="hidden" name="menu[@{{ $index }}][id]" ng-if="item.id > 0" value="@{{ item.id }}" />
                                        <input type="hidden" name="menu[@{{ $index }}][order]" value="@{{ $index }}" />
                                        <div class="col-xs-8 col-sm-10">
                                            <div class="input-group">
                                                <label class="input-group-addon mover">
                                                    <span class="glyphicon glyphicon-menu-hamburger"></span>
                                                </label>
                                                <input name="menu[@{{ $index }}][text]" value="@{{ item.text }}" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="col-xs-4 col-sm-2">
                                            <span class="btn btn-danger form-control" ng-click="mensaMenu.removeItem($index)">X</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-8 col-sm-10" ng-if="mensaMenu.items.length == 0">Voeg hier je menu toe! :)</div>
                                    <div class="col-xs-4 col-sm-2" ng-class="mensaMenu.items.length != 0 ? 'col-xs-offset-8 col-sm-offset-10' : ''">
                                        <span class="btn btn-success form-control" ng-click="mensaMenu.addNew()">+</span>
                                    </div>
                                </div>

                                @if ($errors->has('menu.*'))
                                    @foreach($errors->get('menu.*') as $error)
                                        <span class="help-block">
                                            <strong>{{ $error[0] }}</strong>
                                        </span>
                                    @endforeach
                                @endif
                            </fieldset>
                            <br />
                            <fieldset ng-controller="MensaEditor as mensa">
                                <legend>Prijs opties:</legend>
                                <div class="row price-options" ng-repeat="price in mensa.prices">
                                    <input type="hidden" name="price[@{{ $index }}][id]" ng-if="price.id > 0" value="@{{ price.id }}" />
                                    <div class="@admin col-xs-12 col-sm-7 @else col-xs-7 @endadmin">
                                        <input name="price[@{{ $index }}][description]" ng-disabled="$index==0" value="@{{ $index==0?'Default':price.description }}" class="form-control" @notadmin readonly @endnotadmin />
                                    </div>
                                    <div class="@admin col-xs-8 col-sm-3 @else col-xs-5 @endadmin">
                                        <div class="input-group">
                                            <label class="input-group-addon">
                                                <span class="glyphicon glyphicon-euro"></span>
                                            </label>
                                            <input name="price[@{{ $index }}][price]" ng-value="@{{ price.price }}" type="number" step="0.05" class="form-control" @notadmin readonly @endnotadmin />
                                        </div>
                                    </div>
                                    @admin
                                    <div class="col-xs-4 col-sm-2">
                                        <span class="btn btn-danger disabled form-control" ng-if="$index==0">X</span>
                                        <span class="btn btn-danger form-control" ng-if="$index>0" ng-click="mensa.removePrice($index)">X</span>
                                    </div>
                                    @endadmin
                                </div>
                                @admin
                                <div class="row">
                                    <div class="col-xs-4 col-xs-offset-8 col-sm-2 col-sm-offset-10">
                                        <span class="btn btn-success form-control" ng-click="mensa.addNew()">+</span>
                                    </div>
                                </div>
                                @endadmin

                                @if ($errors->has('price.*'))
                                    @foreach($errors->get('price.*') as $error)
                                        <span class="help-block">
                                            <strong>{{ $error[0] }}</strong>
                                        </span>
                                    @endforeach
                                @endif
                            </fieldset>
                            <br /><br />
                            <input type="submit" value="{{ (!$mensa->id)?'Mensa aanmaken':'Wijzig mensa' }}" class="btn btn-primary" />&nbsp;&nbsp;<a href="{{ (!$mensa->id)?route('home'):route('mensa.overview', ['id' => $mensa->id]) }}" class="btn btn-default">Terug</a>
                        </form>
                    </div>
                    <script type="text/javascript">

                        // Date time script
                        $(function () {
                            moment.updateLocale('en', {
                                week: { dow: 1 } // Monday is the first day of the week
                            });

                            $('#date').datetimepicker({
                                format: 'DD-MM-YYYY HH:mm',
                                defaultDate: moment('{{ old('date', $mensa->date) }}'),
                                stepping: 5
                            });
                            $('#closing_time').datetimepicker({
                                format: 'DD-MM-YYYY HH:mm',
                                defaultDate: moment('{{ old('closing_time', $mensa->closing_time) }}'),
                                stepping: 5
                            });

                            $("#date").on("dp.change", function (e) {
                                var datePicker = $('#closing_time').data("DateTimePicker");
                                var date = datePicker.date();
                                date.set('year', e.date.get('year')).set('month', e.date.get('month')).set('date', e.date.get('date'));
                                datePicker.date(date);
                            });

                            $("#closing_time").on("dp.change", function (e) {
                                var datePicker = $("#date").data("DateTimePicker");
                                if(e.date.unix() <= datePicker.date().unix())
                                    return;

                                var date = datePicker.date();
                                date.set('year', e.date.get('year')).set('month', e.date.get('month')).set('date', e.date.get('date'));
                                datePicker.date(date);
                            });
                        });

                        // Price list
                        angular.module('mensa', ['ui.sortable'])
                            .controller('MensaEditor', function() {
                                var mensa = this;
                                mensa.prices = {!! json_encode(old('price', $mensa->jsonPrices())) !!};

                                mensa.addNew = function() {
                                    mensa.prices.push({text:"", price:0});
                                    window.setTimeout(function() {
                                        $('[name="price['+(mensa.prices.length-1)+'][description]"]').focus();
                                    }, 100)
                                };

                                mensa.removePrice = function(id){
                                    mensa.prices.splice(id, 1);
                                };
                            }).controller('MenuEditor', function(){
                                var menu = this;
                                menu.items = {!! json_encode(old('menu', $mensa->menuItems->toArray())) !!};

                                menu.addNew = function() {
                                    menu.items.push({ text: "" });
                                    window.setTimeout(function() {
                                        $('[name="menu['+(menu.items.length-1)+'][text]"]').focus();
                                    }, 100)
                                };

                                menu.removeItem = function(id){
                                    menu.items.splice(id, 1);
                                };
                            });
                    </script>
                </div>
            </div>
        </div>
    </div>
@endsection