<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Аккаунт</title>
    <link rel="stylesheet" href="{{url('css/account_cabinet.css')}}"/>
    <link href="{{url('bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
</head>
<body>
@include('components.header')

<main class="container d-flex flex-column gap-4 main mb-5 mt-5">
    <h1>Аккаунт</h1>
    <ul class="data list-unstyled">
        <li class="data-item">
            <img class="avatar" src={{$avatarPath}} />
            <button class="btn btn-warning open-popup-button open-popup-button_change-avatar">Изменить</button>
        </li>
        <li class="data-item">
            <dl>
                <dt>имя и фамилия</dt>
                <dd>{{$user->first_name . " " . $user->last_name}}</dd>
            </dl>
            <button class="btn btn-warning open-popup-button open-popup-button_change-name">Изменить</button>
        </li>
        <li class="data-item">
            <dl>
                <dt>псевданим</dt>
                <dd>{{$user->nickname}}</dd>
            </dl>
            <button class="btn btn-warning open-popup-button open-popup-button_change-nickname">Изменить</button>
        </li>
        <li class="data-item">
            <dl>
                <dt>почта</dt>
                <dd>{{$user->email}}</dd>
            </dl>
        </li>
        <li class="data-item">
            <dl>
                <dt>пароль</dt>
                <dd>******</dd>
            </dl>
            <button class="btn btn-warning open-popup-button open-popup-button_change-password">Изменить</button>
        </li>
        <li class="data-item">
            <dl>
                <dt>дата рождения</dt>
                <dd>{{$user->birthday}}</dd>
            </dl>
            <button class="btn btn-warning open-popup-button open-popup-button_change-birthday">Изменить</button>
        </li>
        <li class="data-item">
            <dl>
                <dt>адресс</dt>
                <dd>г. {{$user->city}} ул. {{$user->street}} д. {{$user->house}} {{$user->apartment ? "кв." . $user->apartment : ""}}</dd>
            </dl>
            <button class="btn btn-warning open-popup-button open-popup-button_change-address">Изменить</button>
        </li>
        <li class="data-item">
            <dl>
                <dt>права</dt>
                <dd>Покупатель {{($user->is_seller) ? "Продавец" : ""}} {{($user->is_seller) ? "Администратор" : ""}}</dd>
            </dl>
        </li>
        <li><hr/></li>
        <li>
            @if($user->is_seller)
                <button class="btn btn-warning" onclick="location.href='/seller'">ПАНЕЛЬ ПРОДАВЦА</button>
            @endif
            @if($user->is_admin)
                <button class="btn btn-warning" onclick="location.href='/admin'">АДМИН ПАНЕЛЬ</button>
            @endif
        </li>
        <li>
            <button class="btn btn-warning button-logout" onclick="location.href='/logout'">Выйти из системы</button>
        </li>
    </ul>
</main>

@include('components.footer')

<aside class="popup-bg">
    @include('components.popups.authorization_popup')
    @include('components.popups.registration_popup')
    @include('components.popups.account_cabinet_popups')
</aside>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
</body>
</html>
