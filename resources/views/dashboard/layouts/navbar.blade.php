
<nav id="sidebar-left" class="navbar navbar-expand-md p-0 flex-md-column">
        <div class="navbar-brand m-3">
        
        </div>

    <button class="navbar-toggler navbar-dark ml-auto py-0 m-3" type="button" data-toggle="collapse" data-target="#menu" aria-controls="menu" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="menu">
        <ul class="navbar-nav flex-column ">
            <li class="nav-item" >
                <a class="nav-link" href="{{route('homepage')}}">
                    <i class="fas fa-home"></i>{{"Panel użytkownika"}}
                </a>
            </li>
            <li class="nav-item {{ in_array(Route::currentRouteName(), ['dashboard.product_proposition.index']) ? 'active' : '' }}" >
                <a class="nav-link" href="{{route('dashboard.product_proposition.index')}}">
                    <i class="fas fa-plus-square"></i>{{"Propozycje produktów"}}
                </a>
            </li>
            @if(Auth::user()->hasRole('admin'))
            <li class="nav-item {{ in_array(Route::currentRouteName(), ['recipe_category.index', 'recipe_category.create', 'recipe_category.edit']) ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('recipe_category.index') }}">
                    <i class="fab fa-buffer"></i>{{"Kategorie przepisów"}}
                </a>
            </li>
            <li class="nav-item {{ in_array(Route::currentRouteName(), ['product_category.index', 'product_category.create', 'product_category.edit']) ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('product_category.index') }}">
                    <i class="fab fa-buffer"></i>{{"Kategorie produktów"}}
                </a>
            </li>
            <li class="nav-item {{ in_array(Route::currentRouteName(), []) ? 'active' : '' }}">
                <a class="nav-link" href="{{route('dashboard.recipe.index')}}">
                    <i class="fas fa-cookie-bite"></i>{{"Przepisy"}}
                </a>
            </li>
            @endif
            <li class="nav-item send">
                <a class="nav-link" href="http://localhost:8000/logout" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" >
                    <i class="fas fa-sign-out-alt"></i>{{"wyloguj"}}
                </a>
            </li>
            <form id="logout-form" action="http://localhost:8000/logout" method="POST" class="d-none">
                @csrf         
            </form>
        </ul>
    </div>
</nav>