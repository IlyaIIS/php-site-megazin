<ul class="d-flex flex-row justify-content-between mt-1 nav nav-sort-bar nav-tabs">
    <li class="align-items-baseline d-flex dropdown nav-item">
        <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">
            Категории </a>
        <ul class="dropdown-menu">
            <li class="dropend">
                <a class="dropdown-item dropdown-toggle" data-toggle="dropdown" href="#">
                    Submenu </a>
                <ul class="dropdown-menu dropdown-submenu">
                    <li>
                        <a class="dropdown-item" href="#">Submenu item 1</a>
                    </li>
                    <li><a class="dropdown-item" href="#">Submenu item 3 </a>
                        <ul class="dropdown-menu dropdown-submenu">
                            <li>
                                <a class="dropdown-item" href="#">Multi level 1</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#">Multi level 2</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li class="dropend">
                <button class="dropdown-item dropdown-toggle" data-bs-toggle="dropdown">
                    Dropright
                </button>
                <ul class="dropdown-menu">
                    <li>
                        <a class="dropdown-item" href="#">Action</a>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <a class="dropdown-item" href="#">Separated link</a>
                    </li>
                </ul>
            </li>
        </ul>
        <p>Категория</p>
    </li>
    <li class="align-items-baseline d-flex dropdown nav-item">
        <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">
            Сортировать </a>
        <ul class="dropdown-menu">
            <li class="nav-item">
                <a class="nav-link" href="#" tabindex="-1" aria-disabled="true">По цене &DownArrow;</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" tabindex="-1" aria-disabled="true">По цене &UpArrow;</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" tabindex="-1" aria-disabled="true">По оценке &DownArrow;</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" tabindex="-1" aria-disabled="true">По оценке &UpArrow;</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" tabindex="-1" aria-disabled="true">По дате &DownArrow;</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" tabindex="-1" aria-disabled="true">По дате &UpArrow;</a>
            </li>
        </ul>
        <p class="text-truncate">По цене &DownArrow;</p>
    </li>
    <li class="align-items-center d-flex justify-content-end nav-item price-bound-field">
        <input class="form-control me-2" type="number" placeholder="от" aria-label="от">
        <input class="form-control me-2" type="number" placeholder="до" aria-label="до">
        <button href="#" class="flex-grow-0">
            <img src="{{url('images\reload-svgrepo-com.svg')}}" alt="refresh icon"/>
        </button>
    </li>
</ul>

