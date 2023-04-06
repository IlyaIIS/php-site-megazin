<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>МегаЗин</title>
    <link rel="stylesheet" href="css/home.css"/>
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

@include('components.header')

<main class="container main mt-3">
    <img src="{{url('images\promotions\promotion.jpg')}}" class="promotion-card rounded shadow" alt="Акция!"/>
    <div class="container d-flex flex-wrap gap-2 justify-content-center p-3">
        @for($i = 0; $i < count($products); $i++)
            @include('components.product_card', ['data' => ['imagePath' => $productsImagesPaths[$i], 'product' => $products[$i]]])
        @endfor
    </div>
</main>

@include('components.footer')

<aside class="popup-bg">
    @include('components.popups.authorization_popup')
    @include('components.popups.registration_popup')
</aside>

</body>
</html>
