<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Кабинет администратора</title>
    <link rel="stylesheet" href="css/admin_cabinet.css"/>
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
@include('components.header')

<main class="container d-flex flex-column gap-4 main mb-5 mt-5">
    <h1>Кабинет администратора</h1>
    <input class="form-control" type="text" id="inputNickname" placeholder="Логин пользователя" aria-label="default input example">
    <button class="btn btn-warning button-set-user-seller">Назначить пользователя продавцом</button>
    <button class="btn btn-warning button-unset-user-seller">Отозвать права продавца у пользователя</button>
    <button class="btn btn-warning button-ban-user">Забанить</button>
</main>

@include('components.footer')

<aside class="popup-bg">
    @include('components.popups.authorization_popup')
    @include('components.popups.registration_popup')
</aside>

<script>
    $('.button-set-user-seller').click(function(e) {
        $.ajax({
            type: "PUT",
            url: '/user-set-seller',
            data: {
                'nickname': $('#inputNickname').val(),
                '_token': '{{csrf_token()}}'
            },
            success: function (data) {
                alert("Пользователь сделан продавцом!");
            },
            error: function (jqXHR, text, error) {
                alert(JSON.parse(jqXHR.responseText)['message']);
            },
        });
    });
</script>

<script>
    $('.button-unset-user-seller').click(function(e) {
        $.ajax({
            type: "PUT",
            url: '/user-unset-seller',
            data: {
                'nickname': $('#inputNickname').val(),
                '_token': '{{csrf_token()}}'
            },
            success: function (data) {
                alert("Права продавца отозваны!");
            },
            error: function (jqXHR, text, error) {
                alert(JSON.parse(jqXHR.responseText)['message']);
            },
        });
    });
</script>

<script>
    $('.button-ban-user').click(function(e) {
        $.ajax({
            type: "PUT",
            url: '/user-ban',
            data: {
                'nickname': $('#inputNickname').val(),
                '_token': '{{csrf_token()}}'
            },
            success: function (data) {
                alert("Пользователь забанен!");
            },
            error: function (jqXHR, text, error) {
                alert(JSON.parse(jqXHR.responseText)['message']);
            },
        });
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
</body>
</html>
