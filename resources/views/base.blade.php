<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Mensa') }}</title>

    <!-- Styles -->
    @section('styles')
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
        <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    @show

    <!-- Scripts -->
    @section('scripts')
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    @show
</head>
<body>
    <div>
        <nav class="navbar navbar-default navbar-static-top">
            <div class="container">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <a class="navbar-brand" href="{{ url('/') }}">
                        {{ config('app.name', 'Laravel') }}
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">
                        <li><a href="{{ route('home') }}">Mensaoverzicht</a></li>
                        @admin
                            <li><a href="{{ route('mensa.create') }}">Mensa aanmaken</a></li>
                        @endadmin
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        @guest
                            <li><a href="{{ route('login') }}">Login</a></li>
                        @else
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="{{ route('user.settings') }}">Instellingen</a></li>
                                    @admin
                                        <li><a href="#">Statistiek</a></li>
                                        <li><a href="#">Logboek</a></li>
                                        @if(!Auth::user()->service_user)
                                            <li role="separator" class="divider"></li>
                                        @endif
                                    @endadmin
                                    @if(!Auth::user()->service_user)
                                        <li>
                                            <form id="logout-form" action="{{ route('logout') }}" method="POST">
                                                {{ csrf_field() }}
                                                <input type="submit" class="btn btn-link logout" value="Logout" />
                                            </form>
                                        </li>
                                    @endif
                                </ul>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
        @if(session('error'))
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="panel panel-danger" id="error-panel">
                            <div class="panel-heading">
                                Error
                                <button type="button" class="close" data-target="#error-panel" data-dismiss="alert">
                                    <span aria-hidden="true">&times;</span>
                                    <span class="sr-only">Close</span>
                                </button>
                            </div>
                            <div class="panel-body">
                                {{ session('error') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if(session('warning'))
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="panel panel-warning" id="warning-panel">
                            <div class="panel-heading">
                                Warning
                                <button type="button" class="close" data-target="#warning-panel" data-dismiss="alert">
                                    <span aria-hidden="true">&times;</span>
                                    <span class="sr-only">Close</span>
                                </button>
                            </div>
                            <div class="panel-body">
                                {{ session('warning') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if(session('info'))
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="panel panel-info" id="info-panel">
                            <div class="panel-heading">
                                Info
                                <button type="button" class="close" data-target="#info-panel" data-dismiss="alert">
                                    <span aria-hidden="true">&times;</span>
                                    <span class="sr-only">Close</span>
                                </button>
                            </div>
                            <div class="panel-body">
                                {{ session('info') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @yield('content')
    </div>
</body>
</html>
