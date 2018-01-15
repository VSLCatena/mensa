@extends('mensae.base')

@section('styles')
    @parent
    <link rel="stylesheet" href="{{ asset('css/logs.css') }}" />
@endsection

@section('overview.content')
    <table class="table table-striped responsive-table logs">
        <thead>
        <tr>
            <th>Door</th>
            <th style="width: 100%;">Beschrijving</th>
            <th>Datum</th>
        </tr>
        </thead>
        <tbody>
        @forelse($logs as $log)
            <tr>
                <td style="white-space: nowrap;">{{ ($log->user != null)?$log->user->name:'' }}</td>
                <td>{{ $log->description }}</td>
                <td style="white-space: nowrap;">{{ formatDate($log->created_at) }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="3">Geen logs gevonden!</td>
            </tr>
        @endforelse
        </tbody>
    </table>
@endsection
