<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Кабинет продавца</title>
    <link rel="stylesheet" href="css/seller_cabinet.css"/>
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

@include('components.header')

<main class="container d-flex flex-column gap-4 main mb-5 mt-5">
    <h1>Кабинет продавца</h1>
    <ul class="data list-unstyled">
        <li class="data-item">
            <dl>
                <dt>аватар маназина</dt>
                <img class="shop-avatar" src={{$imagePath}} />
            </dl>
            <button class="btn btn-warning open-popup-button open-popup-button_change-store-avatar">Изменить</button>
        </li>
        <li class="data-item">
            <dl>
                <dt>имя магазина</dt>
                <dd>{{$store->name}}</dd>
            </dl>
            <button class="btn btn-warning open-popup-button open-popup-button_change-store-name">Изменить</button>
        </li>
    </ul>
    <button class="btn btn-warning open-popup-button open-popup-button_add-product">Добавить товар в магазин</button>
</main>

@include('components.footer')

<aside class="popup-bg">
    @include('components.popups.authorization_popup')
    @include('components.popups.registration_popup')
    @include('components.popups.seller_cabinet_popups')
</aside>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
</body>
</html>
