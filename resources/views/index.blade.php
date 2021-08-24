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
    <v-app id="app">
        <v-app-bar app>
            <v-toolbar-title class="me-3">Mensa</v-toolbar-title>
            <v-toolbar-items>
                <v-btn to="/" text>Home</v-btn>
                <v-btn to="/mensa" text>Mensa</v-btn>
            </v-toolbar-items>
            <v-spacer></v-spacer>
            <v-icon large>mdi-account</v-icon>
            <v-icon hidden="hidden" large>mdi-brightness-7</v-icon>
        </v-app-bar>
        <v-main>
            <v-container>
                <router-view></router-view>
            </v-container>
        </v-main>
    </v-app>

    <!-- Scripts -->
    <script src="{{ mix('/js/app.js') }}"></script>
</body>
</html>
