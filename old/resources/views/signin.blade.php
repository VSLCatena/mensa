@extends('base')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-xs-offset-0 col-md-10 col-md-offset-1 col-lg-8 col-lg-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">{{ (!$mensaUser->id)?'Inschrijven':'Inschrijving aanpassen' }} {{ ($mensaUser->user != null && !empty($mensaUser->user->name))?'van '.$mensaUser->user->name:'' }} voor de mensa op {{ formatDate($mensaUser->mensa->date) }}</div>
                    <div class="panel-body">
                        @guest
                            <div class="alert alert-info">
                                <strong>Note:</strong> Als je ingelogd bent hoef je je jezelf niet via de email te bevestigen!
                            </div>
                        @endguest
                        @if($mensaUser->user->email == null && (!Auth::check() || !Auth::user()->service_user))
                            <ul style="padding-left: 15px;">
                                <li>Gebruik het e-mailadres dat bekend is bij {{ config('app.name') }}.</li>
                                <li>Bij meer dan 1 introduc&eacute;, bel de bar: {{ config('mensa.contact.bar') }}.</li>
                                <li>Bevestig de reserveringsemail binnen 15 minuten, anders vervalt je reservering.</li>
                            </ul>
                        @endif
                        <div class="row">
                            <form class="col-xs-12 col-md-8" method="POST">
                                {{ csrf_field() }}
                                @if($mensaUser->user->email == null && !Session::has('extra_lidnummer'))
                                    <div class="form-group">
                                        <label for="email">Email:</label>
                                        <input id="email" type="email" name="email" value="{{ old('email', Auth::check()?Auth::user()->email:'') }}" class="form-control" />
                                    </div>
                                @endif
                                <div class="form-group">
                                    <input type="checkbox" name="vegetarian" id="vegetarian" class="form-check-input" {{ old('vegetarian', $mensaUser->vegetarian)?'checked':'' }} />
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
                                            <input type="checkbox" id="extra_{{ $option->id }}" name="extra[]" value="{{ $option->id }}" class="form-check-input" {{ ((old('extra') != null) ? in_array($option->id, old('extra')) : $mensaUser->extraOptions->contains($option))?'checked':'' }} />
                                            <label for="extra_{{ $option->id }}">{{ $option->description }} (+&euro;{{ $option->price }})</label><br />
                                        @endforeach
                                    </div>
                                    <br />
                                @endif
                                <br />
                                @if(isset($introUser))
                                    <div class="form-group">
                                        <input type="checkbox" name="intro" id="intro" class="form-check-input" {{ old('intro', $introUser->id==true)?'checked':'' }} />
                                        <label for="intro">Met 1 intro</label>
                                    </div>
                                    <div class="intro well" style="{{ (old('intro') || $introUser->id != null)?'':'display: none;' }}">
                                        <div class="form-group">
                                            <input type="checkbox" name="intro_vegetarian" id="intro_vegetarian" class="form-check-input" {{ old('intro_vegetarian', $introUser->vegetarian)?'checked':'' }} />
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
                                                    <input type="checkbox" id="intro_extra_{{ $option->id }}" name="intro_extra[]" value="{{ $option->id }}" class="form-check-input" {{ ((old('intro_extra') != null) ? in_array($option->id, old('intro_extra')) : $introUser->extraOptions->contains($option))?'checked':'' }} />
                                                    <label for="intro_extra_{{ $option->id }}">{{ $option->description }} (&euro;{{ $option->price }})</label><br />
                                                @endforeach
                                            </div>
                                            <br />
                                        @endif
                                    </div>
                                    <div class="intro" style="{{ (old('intro') || $introUser->id != null)?'':'display: none;' }}">
                                        <br />
                                        <br />
                                    </div>
                                @endif
                                <div class="form-group">
                                    <input type="checkbox" id="dishwasher" name="dishwasher" class="form-check-input" {{ old('dishwasher', $mensaUser->dishwasher)?'checked':'' }} />
                                    <label for="dishwasher">Vrijwillig afwassen <span class="intro" style="{{ (isset($introUser) && $introUser->id==true)?'':'display: none;' }}">(met intro)</span></label>
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
    </div>
@endsection
