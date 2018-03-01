@extends('base')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-default">
                <div class="panel-heading">FAQ - Frequently Asked Questions</div>
                <div class="panel-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th style="width: 50%">Vraag</th>
                                <th style="width: 50%">Antwoord</th>
                                <th class="text-nowrap">Laatst gewijzigd door</th>
                                <th>Edit</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($faqs as $faq)
                                <tr>
                                    <td>{{ $faq->question }}</td>
                                    <td>{{ $faq->answer }}</td>
                                    <td class="text-nowrap">{{ $faq->lastEditedBy->name }}</td>
                                    <td class="text-center"><a href="{{ route('faq.edit', ['id' => $faq->id]) }}">X</a></td>
                                    <td class="text-center"><a href="{{ route('faq.delete', ['id' => $faq->id]) }}">X</a></td>
                                </tr>
                            @empty
                                <tr><td colspan="5">Geen FAQ's beschikbaar!</td></tr>
                            @endforelse
                        </tbody>
                    </table>

                    <a href="{{ route('faq.add') }}">Nieuwe FAQ</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection