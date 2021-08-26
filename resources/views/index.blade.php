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
            <v-icon large class="mx-4">mdi-account</v-icon>
            <v-divider vertical></v-divider>
            <v-icon large @click="$toggleDarkMode()" class="mx-4">@{{ $isDarkMode ? 'mdi-brightness-7' : 'mdi-brightness-3' }}</v-icon>
            <v-divider vertical></v-divider>
            <div class="ml-4 py-2" style="height: 100%; cursor: pointer">
                <img style="height: 100%;" src="{{ asset('images/NL.svg') }}" @click="$toggleLanguage()" v-if="$currentLanguage.language.language !== 'nl'" />
                <img style="height: 100%;" src="{{ asset('images/GB.svg') }}" @click="$toggleLanguage()" v-if="$currentLanguage.language.language !== 'en'" />
            </div>
        </v-app-bar>
        <v-main>
            <v-container class="col-12 col-md-offset-1 col-md-10 col-lg-offset-2 col-lg-8 col-xl-offset-3 col-xl-6">
                <router-view></router-view>
            </v-container>
        </v-main>
    </v-app>

    <!-- Scripts -->
    <script src="{{ mix('/js/app.js') }}"></script>
</body>
</html>
