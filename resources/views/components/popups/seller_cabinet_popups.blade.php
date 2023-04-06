<article class="popup popup_change-store-avatar d-flex flex-column closed">
    <div class="align-items-baseline d-flex gap-3 justify-content-between">
        <h2 class="modal-title">Изменить аватар</h2>
        <button type="button" class="btn-close popup__close-button" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <hr/>
    <form data-pg-collapsed class="d-flex flex-column gap-3" enctype="multipart/form-data">
        @csrf
        <div class="mb-3" data-pg-collapsed>
            <label for="inputImage" class="form-label">Добавить изображение</label>
            <input class="form-control" type="file" name="inputImage">
        </div>
        <button type="submit" class="btn btn-warning popup__close-button">Изменить</button>
    </form>
</article>

<article class="popup popup_change-store-name d-flex flex-column closed">
    <div class="align-items-baseline d-flex gap-3 justify-content-between">
        <h2 class="modal-title">Изменить псевданим</h2>
        <button type="button" class="btn-close popup__close-button" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <hr/>
    <form data-pg-collapsed class="d-flex flex-column gap-3">
        @csrf
        <label><input type="text" class="form-control" name="inputName" placeholder="Имя магазина"></label>
        <button type="submit" class="btn btn-warning popup__close-button">Изменить</button>
    </form>
</article>

<article class="popup popup_add-product d-flex flex-column">
    <div class="align-items-baseline d-flex gap-3 justify-content-between">
        <h2 class="modal-title">Описание товара</h2>
        <button type="button" class="btn-close popup__close-button" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <hr/>
    <form class="d-flex flex-column gap-3">
        @csrf
        <label><input type="text" class="form-control" name="inputName" placeholder="Название товара"></label>
        <label><textarea class="form-control" name="inputDescription" placeholder="Описание"></textarea></label>
        <label><input type="number" class="form-control" name="inputPrice" placeholder="Цена"></label>
        <label><input type="number" class="form-control" name="inputCount" placeholder="Количество"></label>
        <div class="align-items-baseline d-flex dropdown gap-3 nav-item">
            <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">
                Категории </a>
            <ul class="dropdown-menu">
                @foreach($categories as $key => $category)
                    @if(array_key_exists('subcategories', $category))
                        <li class="dropend">
                            <a class="dropdown-item dropdown-toggle" data-toggle="dropdown" href="#">Submenu </a>
                            <ul class="dropdown-menu dropdown-submenu">
                                <li><a class="dropdown-item" href="#">Submenu item 1</a></li>
                                <li>
                                    <a class="dropdown-item dropdown-toggle" href="#">Submenu item 3 </a>
                                    <ul class="dropdown-menu dropdown-submenu">
                                        <li><a class="dropdown-item" href="#">Multi level 1</a></li>
                                        <li><a class="dropdown-item" href="#">Multi level 2</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li><a class="dropdown-item category" category_id={{$key}}>{{$category['name']}}</a></li>
                    @endif
                @endforeach
            </ul>
            <label for="inputCategoryId"></label>
            <input name="inputCategoryId" id="selected-category-input" type="hidden"/>
            <span class="selected-category">Категория</span>
        </div>
        <div class="card overflow-auto">
            <ul class="card-body">
            </ul>
        </div>
        <div class="mb-3">
            <label for="inputImage" class="form-label">Добавить изображение</label>
            <input class="form-control" type="file" name="inputImage">
        </div>
        <button type="submit" class="btn btn-warning popup__close-button">Добавить товар</button>
    </form>
</article>

<script>
    $(function () {
        $(".popup_add-product form").submit(function (ev) {
            ev.preventDefault();
            let formData = new FormData(this);
            $.ajax({
                type: "POST",
                url: '/add-product',
                data: formData,
                success: function (data) {
                    alert("Товар успешно добавлен!");
                },
                error: function (jqXHR, text, error) {
                    alert(JSON.parse(jqXHR.responseText)['message']);
                },
                cache: false,
                contentType: false,
                processData: false
            });
            return false;
        });
    });
</script>

<script>
    $(function () {
        $(".popup_change-store-name form").submit(function () {
            let data = $(this).serialize();
            data += '&fromUrl=' + window.location.pathname;
            $.ajax({
                type: "POST",
                url: '/modify-store-name',
                data: data,
                success: function (data) {
                    location.reload();
                },
                error: function (jqXHR, text, error) {
                    alert(JSON.parse(jqXHR.responseText)['message']);
                }
            });
            return false;
        });
    });
</script>

<script>
    $(function () {
        $(".popup_change-store-avatar form").submit(function (ev) {
            ev.preventDefault();
            let formData = new FormData(this);

            $.ajax({
                type: "POST",
                url: '/modify-store-avatar',
                data: formData,
                success: function (data) {
                    location.reload();
                },
                error: function (jqXHR, text, error) {
                    alert(JSON.parse(jqXHR.responseText)['message']);
                },
                cache: false,
                contentType: false,
                processData: false
            });
            return false;
        });
    });
</script>

@vite('resources/js/categorySelection.js')
