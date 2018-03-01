@extends('base')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <div class="panel panel-default">
                    <div class="panel-heading">FAQ @if($faq->id == null) Toevoegen @else Aanpassen @endif</div>
                    <div class="panel-body">
                        <form class="form-horizontal col-md-6 col-xs-12" method="POST">
                            {{ csrf_field() }}

                            <div class="form-group">
                                <label for="question">Vraag</label>
                                <input id="question" class="form-control" name="question" value="{{ old('question', $faq->question) }}">

                                @if ($errors->has('question'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('question') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="answer">Antwoord</label>
                                <input id="answer" class="form-control" name="answer" value="{{ old('answer', $faq->answer) }}">

                                @if ($errors->has('answer'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('answer') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">
                                    @if($faq->id == null) Toevoegen @else Aanpassen @endif
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection