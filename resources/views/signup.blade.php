@extends('base')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Inschrijven voor de mensa op {{ formatDate($mensa->date) }}</div>
                    <div class="panel-body">
                        @auth
                            <div class="alert alert-info">
                                <strong>Note:</strong> Als je ingelogd bent hoef je je voor jezelf niet te bevestigen!
                            </div>
                        @endauth
                        <form method="POST">
                            {{ csrf_field() }}
                            <input type="hidden" name="id" value="{{ $mensa->id }}" />
                            <input type="hidden" name="signup" value="true" />
                            <div class="form-group">
                                <label for="email">Email:</label>
                                <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control"  />
                            </div>
                            <div class="form-group">
                                <label for="wishes">Wensen:</label>
                                <input id="wishes" name="wishes" value="{{ old('wishes', $user->wishes) }}" class="form-control"  />
                            </div>
                            <div class="form-group">
                                <label for="allergies">Allergie&euml;n:</label>
                                <input id="allergies" name="allergies" value="{{ old('allergies', $user->allergies) }}" class="form-control"  />
                            </div>
                            <div class="form-group">
                                <input type="checkbox" id="dishes" name="dishes" class="form-check-input" />
                                <label for="dishes">Vrijwillig afwassen</label>
                            </div>
                            <input type="submit" value="Inschrijven" class="btn btn-primary" />&nbsp;&nbsp;<a href="{{ route('home') }}" class="btn btn-default">Terug</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
