<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Mensa') }}</title>

    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@5.x/css/materialdesignicons.min.css" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">

</head>
<body>
<div id="app">
    <!--suppress HtmlUnknownTag, CheckEmptyScriptTag -->
    <App nlimage="{{ asset('images/NL.svg') }}" enimage="{{ asset('images/GB.svg') }}"/>
</div>
<!-- Scripts -->
<script src="{{ mix('/js/app.js') }}"></script>
</body>
</html>