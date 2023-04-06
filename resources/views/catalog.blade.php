<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Каталог</title>
    <link rel="stylesheet" href="{{url('css/catalog.css')}}"/>
    <link href="{{url('bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
</head>
<body>

@include('components.header')

<main class="container main mt-3">
    @include('components.sort_bar')
    <div class="container d-flex flex-wrap gap-2 justify-content-center p-3">
        @for($i = 0; $i < count($products); $i++)
            @include('components.product_card', ['data' => ['imagePath' => $productsImagesPaths[$i], 'product' => $products[$i]]])
        @endfor
    </div>
    @include('components.page_navigation')
</main>

@include('components.footer')

<aside class="popup-bg">
    @include('components.popups.authorization_popup')
    @include('components.popups.registration_popup')
</aside>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
</body>
</html>
