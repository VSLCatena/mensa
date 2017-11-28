@extends('base')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <div class="panel panel-default">
                    <div class="panel-heading">{{ (!$mensaUser->id)?'Inschrijven':'Inschrijving aanpassen' }} voor de mensa op {{ formatDate($mensaUser->mensa->date) }}</div>
                    <div class="panel-body">
                        @guest
                            <div class="alert alert-info">
                                <strong>Note:</strong> Als je ingelogd bent hoef je je jezelf niet via de email te bevestigen!
                            </div>
                        @endguest
                        <form method="POST">
                            {{ csrf_field() }}
                            <input type="hidden" name="id" value="{{ $mensaUser->mensa->id }}" />
                            @if(!empty($userToken))
                                <input type="hidden" name="userToken" value="{{ $userToken }}" />
                            @endif
                            <input type="hidden" name="signup" value="true" />
                            <div class="form-group">
                                <label for="email">Email:</label>
                                <input id="email" type="email" name="email" value="{{ old('email', $mensaUser->user->email) }}" class="form-control" {{ (session('asAdmin') || $mensaUser->id)?'readonly': '' }} />
                            </div>
                            <div class="form-group">
                                <input type="checkbox" name="vegetarian" id="vegetarian" class="form-check-input" {{ $mensaUser->vegetarian?'checked':'' }} />
                                <label for="vegetarian">Vegetarisch</label>
                            </div>
                            <div class="form-group">
                                <label for="allergies">Allergie&euml;n:</label>
                                <input id="allergies" name="allergies" value="{{ old('allergies', $mensaUser->allergies) }}" class="form-control"  />
                            </div>
                            <div class="form-group">
                                <label for="extrainfo">Extra informatie:</label>
                                <input id="extrainfo" name="extrainfo" value="{{ old('extrainfo', $mensaUser->extra_info) }}" class="form-control"  />
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
                            <br />
                            <div class="form-group">
                                <input type="checkbox" name="intro" id="intro" class="form-check-input" {{ $introUser->id==true?'checked':'' }} />
                                <label for="intro">Met 1 intro</label>
                            </div>
                            <div class="intro" style="{{ $introUser->id==true?'':'display: none;' }}">
                                <div class="form-group">
                                    <input type="checkbox" name="intro_vegetarian" id="intro_vegetarian" class="form-check-input" {{ $introUser->vegetarian?'checked':'' }} />
                                    <label for="intro_vegetarian">Intro vegetarisch</label>
                                </div>
                                <div class="form-group">
                                    <label for="intro_allergies">Intro allergie&euml;n:</label>
                                    <input id="intro_allergies" name="intro_allergies" value="{{ old('intro_allergies', $introUser->allergies) }}" class="form-control"  />
                                </div>
                                <div class="form-group">
                                    <label for="intro_extrainfo">Extra informatie:</label>
                                    <input id="intro_extrainfo" name="intro_extrainfo" value="{{ old('intro_extrainfo', $introUser->extra_info) }}" class="form-control"  />
                                </div>
                                @if($mensaUser->mensa->extraOptions()->count() > 0)
                                    <div class="form-group">
                                        <label>Intro's extra opties:</label><br />
                                        @foreach($mensaUser->mensa->extraOptions()->get() as $option)
                                            <input type="checkbox" id="intro_extra_{{ $option->id }}" name="intro_extra[]" value="{{ $option->id }}" class="form-check-input" {{ $introUser->extraOptions->contains($option)?'checked':'' }} />
                                            <label for="intro_extra_{{ $option->id }}">{{ $option->description }} (&euro;{{ $option->price }})</label><br />
                                        @endforeach
                                    </div>
                                    <br />
                                @endif
                                <br />
                                <br />
                            </div>
                            <div class="form-group">
                                <input type="checkbox" id="dishwasher" name="dishwasher" class="form-check-input" {{ old('dishwasher', $mensaUser->dishwasher)?'checked':'' }} />
                                <label for="dishwasher">Vrijwillig afwassen <span class="intro" style="{{ $introUser->id==true?'':'display: none;' }}">(met intro)</span></label>
                            </div>
                            @admin
                                <div class="form-group">
                                    <input type="checkbox" id="cooks" name="cooks" class="form-check-input" {{ old('cooks', $mensaUser->cooks)?'checked':'' }} />
                                    <label for="cooks">Koker</label>
                                </div>
                            @endadmin
                            <input type="submit" value="{{ (!$mensaUser->id)?'Inschrijven':'Inschrijving aanpassen' }}" class="btn btn-primary" />&nbsp;&nbsp;<a href="{{ (Auth::check() && Auth::user()->mensa_admin)?route('mensa.signins', ['id' => $mensaUser->mensa->id]):route('home') }}" class="btn btn-default">Terug</a>
                        </form>
                        <script type="text/javascript">
                            $("#intro").click(function(){
                                $(".intro").toggle($(this).prop('checked'));
                            })
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
