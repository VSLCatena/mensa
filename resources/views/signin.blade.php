@extends('base')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Inschrijven voor de mensa op {{ formatDate($mensaUser->mensa->date) }}</div>
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
                                <input id="email" type="email" name="email" value="{{ old('email', $mensaUser->user->email) }}" class="form-control" {{ session('asAdmin')?'readonly': '' }} />
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
                                <input type="checkbox" name="intro" id="intro" class="form-check-input" {{ $introUser->id==true?'checked':'' }} />
                                <label>Met 1 intro</label>
                            </div>
                            <div class="intro" style="{{ $introUser->id==true?'':'display: none;' }}">
                                <div class="form-group">
                                    <label for="intro_wishes">Intro wensen:</label>
                                    <input id="intro_wishes" name="intro_wishes" value="{{ old('intro_wishes', $introUser->wishes) }}" class="form-control"  />
                                </div>
                                <div class="form-group">
                                    <label for="intro_allergies">Intro allergie&euml;n:</label>
                                    <input id="intro_allergies" name="intro_allergies" value="{{ old('intro_allergies', $introUser->allergies) }}" class="form-control"  />
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
                            </div>

                            <div class="form-group">
                                <input type="checkbox" id="dishwasher" name="dishwasher" class="form-check-input" />
                                <label for="dishwasher">Vrijwillig afwassen <span class="intro" style="display: none;">(met intro)</span></label>
                            </div>
                            <input type="submit" value="Inschrijven" class="btn btn-primary" />&nbsp;&nbsp;<a href="{{ route('home') }}" class="btn btn-default">Terug</a>
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
