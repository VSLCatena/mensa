@extends('mensae.base')

@section('overview.content')
    <table class="table table-striped">
        <thead>
        <tr>
            <th>Door</th>
            <th style="width: 100%;">Beschrijving</th>
            <th>Datum</th>
        </tr>
        </thead>
        <tbody>
        @foreach($logs as $log)
            <tr>
                <td style="white-space: nowrap;">{{ ($log->user != null)?$log->user->name:'' }}</td>
                <td>{{ $log->description }}</td>
                <td style="white-space: nowrap;">{{ formatDate($log->created_at) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
