
<nav id="sidebar-left" class="navbar navbar-expand-md p-0 flex-md-column">
        <div class="navbar-brand m-3">
        <div class="btn-group float-right">
            <button type="button" class=" align-items-center btn btn-md btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mx-3 text-size-medium">{{ "lang" }}</span>
            </button>

            <div class="dropdown-menu">
                <a class="dropdown-item d-flex align-items-center text-size-medium pr-5" href="#"><span class="mx-3">{{ "pl"}}</span></a>
                <a class="dropdown-item d-flex align-items-center text-size-medium pr-5" href="#"><span class="mx-3">{{"en"}}</span></a>
            </div>
        </div>
    </div>

    <button class="navbar-toggler navbar-dark ml-auto py-0 m-3" type="button" data-toggle="collapse" data-target="#menu" aria-controls="menu" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="menu">
        <ul class="navbar-nav flex-column ">
            <li class="nav-item {{ in_array(Route::currentRouteName(), ['product.index', 'product.create', 'product.edit']) ? 'active' : '' }}" >
                <a class="nav-link" href="#">
                    <i class="fas fa-cube inline-block"></i>{{"products"}}
                </a>
            </li>
            <li class="nav-item {{ in_array(Route::currentRouteName(), ['category.index', 'category.create', 'category.edit']) ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('recipe_category.index') }}">
                    <i class="fab fa-buffer"></i>{{"recipe_category"}}
                </a>
            </li>
            <li class="nav-item {{ in_array(Route::currentRouteName(), ['category.index', 'category.create', 'category.edit']) ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('product_category.index') }}">
                    <i class="fab fa-buffer"></i>{{"product_category"}}
                </a>
            </li>
            <li class="nav-item {{ in_array(Route::currentRouteName(), ['shoppingCart.index', 'shoppingCart.create', 'shoppingCart.edit']) ? 'active' : '' }}">
                <a class="nav-link" href="#">
                    <i class="fas fa-shopping-basket"></i>{{"products"}}
                </a>
            </li>
            <li class="nav-item {{ in_array(Route::currentRouteName(), []) ? 'active' : '' }}">
                <a class="nav-link" href="#">
                    <i class="fas fa-luggage-cart"></i>{{"products"}}
                </a>
            </li>
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