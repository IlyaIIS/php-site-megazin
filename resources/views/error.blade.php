<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ошибка!</title>
    <link rel="stylesheet" href="css/error.css"/>
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

@include('components.header')

<main class="container d-flex flex-column gap-4 main mb-5 mt-5">
    <section class="error-area align-self-center">
        <h1>Ошибка!</h1>
        <p>{{$errorText ? $errorText : "Отсутствует описание ошибки."}}</p>
    </section>
</main>

@include('components.footer')

<aside class="popup-bg">
    @include('components.popups.authorization_popup')
    @include('components.popups.registration_popup')
</aside>

</body>
</html>
