@extends('base')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Instellingen aanpassen</div>

                    <div class="panel-body">
                        <form class="form-horizontal col-md-6 col-xs-12" method="POST">
                            {{ csrf_field() }}

                            <div class="form-group">
                                <label for="allergies">Allergie&euml;n</label>
                                <input id="allergies" class="form-control" name="allergies" value="{{ old('allergies', Auth::user()->allergies) }}">
                            </div>

                            <div class="form-group">
                                <label for="password">Extra informatie</label>
                                <input id="extra_info" class="form-control" name="extra_info" value="{{ old('extra_info', Auth::user()->extra_info) }}">
                            </div>

                            <div class="form-group">
                                <input type="checkbox" id="vegetarian" class="checkbox-inline" name="vegetarian" value="true" {{ old('vegetarian', Auth::user()->vegetarian) ? 'checked':'' }}>
                                <label for="vegetarian">Vegetarisch</label>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">
                                    Aanpassen
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
