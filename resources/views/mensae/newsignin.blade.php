@extends('mensae.base')

@section('scripts')
    @parent
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular.min.js"></script>
@endsection

@section('overview.content')
    <div  ng-app="userSearch" ng-controller="UserSearch as search" class="col-xs-12">
        <div class="row">
            <div class="col-xs-12">
                <h3>Iemand inschrijven</h3>
                Ook voor het inschrijven van intros vul je hier een verantwoordelijk lid in.
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-md-5">
                <div class="form-group">
                    <label for="searchbar">Naam:</label>
                    <input id="searchbar" type="text" ng-model="search.name" ng-change="requestUsers()" ng-model-options="{ debounce: 500 }" placeholder="Naam" value="{{ old('name', '') }}" class="form-control typeahead" />
                    <div id="namesearch">
                        <div class="user-result" ng-repeat="user in search.users" ng-click="click(user.name, user.lidnummer)">
                            @{{ user.name }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <form method="POST" action="" class="pull-left">
                {{ csrf_field() }}
                <input type="hidden" name="lidnummer" value="@{{ search.submitLidnummer }}" />
                <input type="submit" class="btn btn-primary" value="Schrijf @{{ search.submitName }} in" disabled="disabled" />
            </form>
            <form method="POST" action="" class="pull-left">
                {{ csrf_field() }}
                <input type="hidden" name="lidnummer" value="@{{ search.submitLidnummer }}" />
                <input type="hidden" name="bulk" value="true" />
                &nbsp;&nbsp;&nbsp;
                <input type="submit" class="btn btn-primary" value="Schrijf (meerdere) intros voor @{{ search.submitName }} in" disabled="disabled" />
            </form>
        </div>
    </div>
    <script type="text/javascript">
        angular.module('userSearch', [])
            .controller('UserSearch', function($scope, $http) {
                var search = this;
                search.name = '';
                search.submitLidnummer = '';
                search.submitName = '';
                search.users = [];
                search.hasFocus = false;
                $scope.requestUsers = function(){
                    $http.post("{{ route('mensa.searchusers') }}", {name: search.name})
                        .then(function(response){
                            search.users = response.data;
                        })
                };

                $scope.click = function(name, lidnummer){
                    search.name = name;
                    search.submitLidnummer = lidnummer;
                    search.submitName = name;
                    $("input[type='submit']").attr('disabled', null);
                };
            });
    </script>
@endsection
