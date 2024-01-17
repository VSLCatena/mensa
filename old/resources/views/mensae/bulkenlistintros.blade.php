@extends('base')

@section('styles')
    @parent
    <link href="{{ asset('css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet">
@endsection

@section('scripts')
    @parent
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular.min.js"></script>
@endsection

@section('content')
    <div class="container" ng-app="mensa" ng-controller="MensaEditor as mensa">
        <div class="row">
            <div class="col-xs-12 col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">{{ 'Intros inschrijven voor '.$user->name.' voor de mensa op '.formatDate($mensa->date, false, false) }}</div>
                    <div class="panel-body">
                        <form method="POST" action="">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <div class="row">
                                    <p class="col-xs-5">Allergie&euml;n</p>
                                    <p class="col-xs-4">Extra informatie:</p>
                                    <div class="col-xs-1">Vego</div>
                                </div>
                                <div ng-repeat="intro in mensa.intros">
                                    <div class="row">
                                        <div class="col-xs-5">
                                            <input name="intro[@{{ $index }}][allergies]" value="@{{ intro.allergies }}" class="form-control" />
                                        </div>
                                        <div class="col-xs-4">
                                            <input name="intro[@{{ $index }}][info]" value="@{{ intro.info }}" class="form-control" />
                                        </div>
                                        <div class="col-xs-1">
                                            <input type="checkbox" name="intro[@{{ $index }}][vegetarian]" value="true" />
                                        </div>
                                        <div class="col-xs-2">
                                            <span class="btn btn-danger form-control" ng-click="mensa.removePrice($index)">X</span>
                                        </div>
                                    </div>
                                    @if($mensa->extraOptions()->count() > 0)
                                        <div class="row">
                                            <div class="col-xs-12">
                                                @foreach($mensa->extraOptions as $extraOption)
                                                    <span style="white-space: nowrap"><input type="checkbox" name="intro[@{{ $index }}][option][{{ $extraOption->id }}]" />
                                                        {{ $extraOption->description }}
                                                        (&euro;{{ number_format($extraOption->price, 2) }})
                                                    </span>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                    <br />
                                </div>
                                <div class="row">
                                    <div class="col-xs-2 col-xs-offset-10">
                                        <span class="btn btn-success form-control" ng-click="mensa.addNew()">+</span>
                                    </div>
                                </div>
                            </div>

                            <input type="submit" value="Intros inschrijven" class="btn btn-primary" />
                            &nbsp;&nbsp;
                            <a href="{{ route('mensa.overview', ['id' => $mensa->id]) }}" class="btn btn-default">Terug</a>
                        </form>
                    </div>
                    <script type="text/javascript">
                        // Price list
                        angular.module('mensa', [])
                            .controller('MensaEditor', function() {
                                var mensa = this;
                                mensa.intros = [{ allergies: '', info: '', options: []}];

                                mensa.addNew = function() {
                                    mensa.intros.push({allergies: '', info:'', options: []});
                                };

                                mensa.removePrice = function(id){
                                    mensa.intros.splice(id, 1);
                                };
                            });
                    </script>
                </div>
            </div>
        </div>
    </div>
@endsection
