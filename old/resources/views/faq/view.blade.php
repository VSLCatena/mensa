@extends('base')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <div class="panel panel-default">
                    <div class="panel-heading">FAQ - Frequently Asked Questions</div>
                    <div class="panel-body">
                        @forelse($faqs as $faq)
                            <div class="faq-item">
                                <strong>{{ $faq->question }}</strong>
                                <p class="faq-indent">{{ $faq->answer }}</p>
                            </div>
                        @empty
                            Geen FAQ's beschikbaar!
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection