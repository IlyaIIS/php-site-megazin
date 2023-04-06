<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{$storeName}}</title>
    <link rel="stylesheet" href="{{url('css/store.css')}}"/>
    <link href="{{url('bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
</head>
<body>

@include('components.header')

<main class="container d-flex flex-column gap-4 main mt-3">
    <div class="d-flex flex-column gap-2 title">
        <div class="align-items-center d-flex gap-5 head">
            <img class="shop-avatar" src="{{url($imagePath)}}" alt="Аватар магазина"/>
            <h1>{{$storeName}}</h1>
        </div>
        <div class="align-items-baseline d-flex elevation gap-1">
            @if($reviewCount > 0)
                <p>4.43</p>
                <img class="star-image" src="{{url('images\star-filled.svg')}}" alt="звезда"/>
                <div class="space"></div>
                <p>(оценок {{$reviewCount}})</p>
            @else
                <p>нет оценок</p>
            @endif
            <div class="space"></div>
            <div class="space"></div>
            <p>покупок {{$orderCount}}</p>
        </div>
    </div>
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
