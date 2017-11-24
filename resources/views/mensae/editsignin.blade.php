@extends('base')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Iemand inschrijven/aanpassen voor de mensa op {{ formatDate($mensaUser->mensa->date) }}</div>
                    <div class="panel-body">
                        <form method="POST">
                            {{ csrf_field() }}
                            <input type="hidden" name="id" value="{{ $mensaUser->mensa->id }}" />
                            <input type="hidden" name="signup" value="true" />
                            <div class="form-group">
                                <label for="email">Email:</label>
                                <input id="email" type="email" name="email" value="{{ old('email', $mensaUser->user->email) }}" class="form-control" readonly />
                            </div>
                            <div class="form-group">
                                <label for="wishes">Wensen:</label>
                                <input id="wishes" name="wishes" value="{{ old('wishes', $mensaUser->wishes) }}" class="form-control"  />
                            </div>
                            <div class="form-group">
                                <label for="allergies">Allergie&euml;n:</label>
                                <input id="allergies" name="allergies" value="{{ old('allergies', $mensaUser->allergies) }}" class="form-control"  />
                            </div>
                            @if($mensaUser->mensa->extraOptions()->count() > 0)
                                <div class="form-group">
                                    <label>Extra opties:</label><br />
                                    @foreach($mensaUser->mensa->extraOptions as $option)
                                        <input type="checkbox" id="extra_{{ $option->id }}" name="extra[]" value="{{ $option->id }}" class="form-check-input" {{ $mensaUser->extraOptions->contains($option)?'checked':'' }} />
                                        <label for="extra_{{ $option->id }}">{{ $option->description }} (+&euro;{{ $option->price }})</label><br />
                                    @endforeach
                                </div>
                                <br />
                            @endif

                            <div class="form-group">
                                <input type="checkbox" id="dishwasher" name="dishwasher" class="form-check-input" {{ old('dishwasher', $mensaUser->dishwasher)?'checked':'' }} />
                                <label for="dishwasher">Afwasser</label>
                                <br />
                                <input type="checkbox" id="cooks" name="cooks" class="form-check-input" {{ old('cooks', $mensaUser->cooks)?'checked':'' }} />
                                <label for="cooks">Koker</label>
                            </div>
                            <input type="submit" value="Inschrijven/aanpassen" class="btn btn-primary" />&nbsp;&nbsp;<a href="{{ route('mensa.signins', ['id' => $mensaUser->mensa->id]) }}" class="btn btn-default">Terug</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
