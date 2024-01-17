@extends('base')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <form method="POST" action="{{ route('faq.delete', ['id' => $faq->id]) }}" class="panel panel-warning">
                    <div class="panel-heading">
                        Weet je zeker dat je deze FAQ wilt verwijderen?
                    </div>
                    <div class="panel-body">
                        {{ csrf_field() }}
                        <input type="submit" class="btn btn-danger" value="Ja" />
                        <a href="{{ route('faq.list', ['id' => $faq->id]) }}" class="btn btn-success">Nee</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
