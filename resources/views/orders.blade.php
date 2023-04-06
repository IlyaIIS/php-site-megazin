<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Заказы</title>
    <link rel="stylesheet" href="{{url('css/orders.css')}}"/>
    <link href="{{url('bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
</head>
<body>

@include('components.header')

<main class="container d-flex flex-column gap-4 main mb-5 mt-5">
    <h1>Ваши заказы</h1>
    <button class="btn btn-warning fw-semibold text-dark" type="button" onclick="location.href='/shopping-cart'" >Перейти к корзине</button>
    <div class="d-flex flex-column gap-4">
        @for($i = 0; $i < count($products); $i++)
            <div class="align-items-center d-flex gap-3 purchase-card">
                <div class="purchase-card__inside border border-4 d-flex gap-2 shadow-sm">
                    <img src="{{url($productsImagesPaths[$i])}}" class="shadow-sm"/>
                    <div class="d-flex flex-column flex-grow-1 justify-content-between mb-1 mt-1">
                        <h4 class="name"> {{$products[$i]->name}} </h4>
                        <div class="align-items-baseline d-flex justify-content-between me-2 row">
                            <h2 class="price col-sm"> {{$orders[$i]->price}}р</h2>
                            <p class="price col-sm"> {{$states[$i]->name}} </p>
                            <p class="price col-sm"> {{(new DateTime($orders[$i]->created_at))->format('Y-m-d')}} </p>
                        </div>
                    </div>
                </div>
                <button class="btn btn-warning fw-semibold text-dark cancel-order-button"
                        type="button"
                        product_id="{{$products[$i]->id}}">
                    ОТВЕНИТЬ
                </button>
            </div>
        @endfor
    </div>
</main>

@include('components.footer')

<aside class="popup-bg ">
    @include('components.popups.authorization_popup')
    @include('components.popups.registration_popup')
</aside>

<script>
    $('.cancel-order-button').click(function(e) {
        $.ajax({
            type: "PUT",
            url: '/cancel-order',
            data: {'productId': $(this).attr('product_id'), '_token': '{{csrf_token()}}' },
            success: function (data) {
                location.reload();
            },
            error: function (jqXHR, text, error) {
                alert(JSON.parse(jqXHR.responseText)['message']);
            },
        });
        return false;
    });
</script>

@vite('resources/js/popupOpenEvents.js')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
</body>
</html>
