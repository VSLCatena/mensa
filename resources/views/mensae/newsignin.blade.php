@extends('mensae.base')

@section('scripts')
    @parent
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular.min.js"></script>
@endsection

@section('overview.content')
    <form method="POST" action="" class="col-xs-12 col-md-5" ng-app="userSearch" ng-controller="UserSearch as search">
        {{ csrf_field() }}
        <input type="hidden" name="email" value="@{{ search.email }}" />
        <div class="form-group">
            <label for="name">Naam:</label>
            <input id="searchbar" autocomplete="nope" type="text" ng-model="search.name" ng-change="requestUsers()" ng-model-options="{ debounce: 500 }" placeholder="Naam" value="{{ old('name', '') }}" class="form-control typeahead" />
            <div id="namesearch">
                <div class="user-result" ng-repeat="user in search.users" ng-click="click(user.name, user.email)">
                    @{{ user.name }}
                </div>
            </div>
        </div>
        <input type="submit" class="btn btn-primary" />
    </form>
    <script type="text/javascript">
        angular.module('userSearch', [])
            .controller('UserSearch', function($scope, $http) {
                var search = this;
                search.email = '';
                search.name = '';
                search.users = [];
                search.hasFocus = false;
                $scope.requestUsers = function(){
                    $http.post("{{ route('mensa.searchusers') }}", {name: search.name})
                        .then(function(response){
                            search.users = response.data;
                            console.debug(response);
                        })
                };

                $scope.click = function(name, email){
                    search.name = name;
                    search.email = email;
                };
            });
    </script>
@endsection
