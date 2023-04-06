<header class="page-borders navbar navbar-expand-lg navbar-light py-4">
    <div class="align-items-start container flex-column gap-3">
        <div class="align-items-center container d-flex flex-row flex-wrap header__title justify-content-between">
            <a class="fw-bold navbar-brand text-warning" href="/home" target>МегаЗин</a>
            <div class="d-flex flex-grow-1 gap-3 justify-content-end">
                @if($is_authorized)
                    <button onclick="location.href='/account'">
                        <img src="{{url('images\user.svg')}}" alt="login"/>
                    </button>
                @else
                    <button class="open-popup-button open-popup-button_authorization">
                        <img src="{{url('images\login-svgrepo-com.svg')}}" alt="login"/>
                    </button>
                @endif
                <button onclick="location.href='/shopping-cart'">
                    <img src="{{url('images\basket-svgrepo-com.svg')}}" alt="product cart"/>
                </button>
                <button onclick="location.href='/orders'">
                    <img src="{{url('images\box-svgrepo-com.svg')}}" alt="orders"/>
                </button>
            </div>
        </div>
        <form class="container d-flex flex-row">
            <label for="input-search"></label>
            <input class="form-control me-2" type="search" id="input-search" name="inputSearch" placeholder="Поиск">
            <button class="btn btn-warning fw-semibold" type="submit">ПОИСК</button>
        </form>
    </div>
</header>
