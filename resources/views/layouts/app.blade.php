<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <base href="{{ url('/') }}" />
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

    <title>@yield('title', __('app.application_title'))</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand font-weight-bolder" href="{{ url('/') }}">
                    {{ __('app.application_name') }}
                </a>
                <button
                class="navbar-toggler" type="button"
                data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false"
                >
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('course.explorer') }}">
                            <i class="fas fa-list-alt"></i>
                            {{ __('app.courses') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('announcement.explorer') }}">
                            <i class="fas fa-bullhorn"></i>
                            {{ __('app.announcements') }}
                        </a>
                    </li>
                </ul>

                <form class="form-inline" action="{{ route('search') }}">
                    @csrf
                    <div class="input-group">
                        <input class="form-control" name="" type="text"
                        placeholder="{{ $search_placeholder }}"/>
                        <div class="input-group-append">
                            <a class="btn btn-outline-success" href="">
                                <div class="fas fa-search"></div>
                            </a>
                        </div>
                    </div>
                </form>

                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ml-auto">
                    <!-- Authentication Links -->
                    @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">
                            <i class="fas fa-sign-in-alt"></i>
                            {{ __('auth.login') }}
                        </a>
                    </li>
                    @if (Route::has('register'))
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">
                            <i class="far fa-id-card"></i>
                            {{ __('auth.register') }}
                        </a>
                    </li>
                    @endif
                    @endguest
                    @auth
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle"
                        href="#" role="button"
                        data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false" v-pre
                        >
                        {{ Auth::user()->name }} <span class="caret"></span>
                    </a>

                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="">
                            <i class="fas fa-file-alt"></i>
                            {{ __('user.my_applications') }}
                        </a>

                        <a class="dropdown-item" href="">
                            <i class="fas fa-list-alt"></i>
                            {{ __('user.my_courses') }}
                        </a>


                        <a class="dropdown-item" href="">
                            <i class="fas fa-user"></i>
                            {{ __('user.my_profile') }}
                        </a>

                        <div class="dropdown-divider"></div>

                        @if (Auth::user()->hasRole('Teacher'))
                        <a class="dropdown-item bg-success text-white"
                        href="{{ route('teacher.dashboard') }}"
                        >
                        <i class="fas fa-chalkboard-teacher"></i>
                        {{ __('user.panel_teacher') }}
                    </a>
                    @endif
                    @if (Auth::user()->hasRole('Organizer'))
                    <a class="dropdown-item bg-primary text-light"
                    href="{{ route('organizer.dashboard') }}"
                    >
                    <i class="fas fa-user-edit"></i>
                    {{ __('user.panel_organizer') }}
                </a>
                @endif
                @if (Auth::user()->hasRole('Admin'))
                <a class="dropdown-item bg-danger text-light"
                href="{{ route('admin.dashboard') }}"
                >
                <i class="fas fa-user-tie"></i>
                {{ __('user.panel_admin') }}
            </a>
            @endif
            <a class="dropdown-item" href="{{ route('logout') }}"
            onclick="event.preventDefault();
            document.getElementById('logout-form').submit();">
            <i class="fas fa-sign-out-alt"></i>
            {{ __('auth.logout') }}
        </a>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </div>
</li>
@endauth
</ul>
</div>
</div>
</nav>
@auth
@if (! Auth::user()->hasVerifiedEmail())
<div class="alert alert-danger text-center" role="alert">
    <i class="fas fa-exclamation-triangle"></i>
    {{ __('verify_email.warning') }}
    <a class="btn btn-primary btn-sm" href="{{ route('verification.notice') }}">
        {{ __('verify_email.verify') }}
    </a>
</div>
@endif
@endauth

<main class="py-4">
    @yield('content')
</main>
</div>
</body>
</html>
