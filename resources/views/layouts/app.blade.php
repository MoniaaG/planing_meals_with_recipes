<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}"/>

    <title>{{ config('app.name', 'Laravel') }}</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    
    
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/homepage.css') }}" rel="stylesheet">

    <script>
        $(document).ready(function() {
            //
            // Append ToolTip into elements with data-toggle equal tooltip
            //
            $('[data-toggle="tooltip"]').tooltip();

            //
            // Ajax request setup
            //
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            //
            // Bootstrap Bootbox Modal setup
            //
            bootbox.setDefaults({
                locale: "",
                backdrop: true,
                centerVertical: true,
            });

            //
            // Logout submit
            //
            $("#logout").click(function() {
                $("#logout-form").submit();
            });

            //
            // Append redirect into nav items and buttons with param href
            //
            $("nav li[href], button[href]").click(function() {
                $(location).attr("href", $(this).attr("href"));
            });

            //
            // Append red * into required form items
            //
            $("input, select, textarea").filter('[required]:visible').each(function() {
                $("label[for='" + $(this).attr("id") + "']").not(".prepend").not(".append").append('<span class="text-danger font-weight-bold">*</span>');
            });
        });

    </script>
    @yield('scripts-start')
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <a class="navbar-brand" href="@guest {{route('homepage')}} @else {{route('home')}} @endguest"><i class="fas fa-home" style="color: cadetblue;"></i></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                @auth
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    @lang('home.recipes')
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="{{ route('recipe.index') }}">@lang('home.my_recipes')</a>
                    <a class="dropdown-item" href="{{ route('recipe.create') }}">@lang('home.add')</a>
                    <a class="dropdown-item" href="{{route('recipe.favourities')}}">@lang('home.favourites')</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="{{ route('recipe.shared') }}">@lang('home.shared_recipes')</a>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('calendar.show') }}">@lang('home.calendar')</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    @lang('home.pantry')
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="{{ route('pantry.index')}}">@lang('home.my_products')</a>
                    <a class="dropdown-item" href="{{ route('pantry.addProduct_create') }}">@lang('home.add')</a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    @lang('home.shopping_list')
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="{{ route('pantry.searchShoppingList') }}">@lang('home.generate_shopping_list')</a>
                    <a class="dropdown-item" href="{{ route('pantry.showList') }}">@lang('home.generated_list')</a>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('product.proposition_create') }}">@lang('home.proposition_products')</a>
                </li>
                @endauth
                </ul>
                <form class="form-inline my-2 my-lg-0" method="get" action="{{route('recipe.search')}}">
                <input class="form-control mr-sm-2" type="search" placeholder="Wyszukaj przepis" aria-label="Search" name="search">
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit"><i class="fas fa-search"></i></button>
                </form>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">@lang('/auth/login_register.login')</a>
                                </li>
                            @endif
                            
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">@lang('/auth/login_register.register')</a>
                                </li>
                            @endif
                        @else
                        <li class="nav-item mt-md-3">
                            <a id="aNotification" href="{{route('notifications')}}" type="button" class="mr-2" data-toggle="modal" data-target="#exampleModal">
                            <i class="far fa-bell pl-2 fas-menu notifications-bell"></i>
                            <span id="notifications" class="notifications @if(isset($notifications) && $notifications->count() === 0 || !isset($notifications)) nothing-to-display @endif">
                                @if(isset($notifications))
                                ({{ $notifications->count() }})
                                @else
                                (0)
                                @endif
                            </span>
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }}
                            </a>

                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                                document.getElementById('logout-form').submit();">
                                    @lang('/auth/login_register.logout')
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
            @include('layouts.footer')
        </main>
    </div>
    @yield('scripts')
    @include('notification')
    @stack('modal')
</body>
</html>
<!-- Button trigger modal -->