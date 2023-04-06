<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Заказать</title>
    <link rel="stylesheet" href="{{url('css/order_making.css')}}"/>
    <link href="{{url('bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
</head>
<body>
@include('components.header')

<main class="container d-flex flex-column gap-4 main mb-5 mt-5">
    <h1> Панель заказа </h1>
    <button class="btn btn-warning fw-semibold text-dark" type="button" onclick="location.href='/shopping-cart'">
        Вернуться в корзину
    </button>
    <div class="align-items-end d-flex justify-content-between main-region">
        <div class="d-flex flex-column gap-4 col product-list">
            @for($i = 0; $i < count($products); $i++)
                <div class="purchase-card purchase-card__inside border border-4 d-flex gap-2 shadow-sm" product_id="{{$products[$i]->id}}">
                    <img src="{{url($productsImagesPaths[$i])}}" class="shadow-sm" alt="изображение товара"/>
                    <div class="d-flex flex-column justify-content-between pb-1 pt-1">
                        <h4 class="name"> {{$products[$i]->name}} </h4>
                        <h2 class="price"> {{$products[$i]->price}} р</h2>
                    </div>
                </div>
            @endfor
        </div>
        <div class="col d-flex flex-column info-region">
            <p>Сумма: {{$totalPrice}} р </p>
            <p>Количество товаров: {{count($products)}} </p>
            <p>Адрес доставки: г. {{$user->city}}, ул. {{$user->street}}, дом {{$user->house}}@if($user->apartment), {{$user->apartment}} @endif </p>
            <button class="btn btn-warning fw-semibold text-dark make-order-button" type="button">Оформить</button>
        </div>
    </div>
</main>

@include('components.footer')

<aside class="popup-bg">
    @include('components.popups.authorization_popup')
    @include('components.popups.registration_popup')
</aside>

<script>
    $('.make-order-button').click(function(e) {
        let productsIds = [];
        $('.product-list').children('.purchase-card').each(function() {
            productsIds.push($(this).attr('product_id'))
        });
        $.ajax({
            type: "POST",
            url: '/order',
            data: {
                'productsIds': productsIds,
                '_token': '{{csrf_token()}}'
            },
            success: function (data) {
                alert("Заказ успешно осуществлён!");
                location.href='/shopping-cart';
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
