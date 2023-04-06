<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Корзина</title>
    <link rel="stylesheet" href="{{url('css/shopping_cart.css')}}"/>
    <link href="{{url('bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
</head>
<body>
@include('components.header')

<main class="container main mb-5 mt-5">
    <h1> Ваша корзина </h1>
    <form class="d-flex flex-column gap-4 mb-5 mt-5" class="cart-form" action="/order-making">
        @csrf
        <button class="btn btn-warning fw-semibold text-dark" type="submit" >Перейти к оформлению</button>
        <div class="d-flex flex-column gap-4">
            @for($i = 0; $i < count($products); $i++)
                <div class="align-items-center d-flex gap-3 purchase-card">
                    <label>
                        <input class="form-check-input" type="checkbox" name="inputCheckbox_{{$products[$i]->id}}">
                    </label>
                    <div class="purchase-card__inside border border-4 d-flex gap-2 shadow-sm">
                        <img src="{{$productsImagesPaths[$i]}}" class="shadow-sm" alt="изображение товара"/>
                        <div class="d-flex flex-column justify-content-between pb-1 pt-1">
                            <h4 class="name"> {{$products[$i]->name}} </h4>
                            <h2 class="price"> {{$products[$i]->price}} р</h2>
                        </div>
                    </div>
                    <button class="btn btn-warning fw-semibold text-dark delete-cart-button"
                            type="button"
                            product_id="{{$products[$i]->id}}">
                        УДАЛИТЬ
                    </button>
                </div>
            @endfor
        </div>
    </form>
</main>

@include('components.footer')

<aside class="popup-bg">
    @include('components.popups.authorization_popup')
    @include('components.popups.registration_popup')
</aside>

<script>
    $('.delete-cart-button').click(function(e) {
        $.ajax({
            type: "DELETE",
            url: '/cart',
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

<script>
    $('.cart-form2').click(function(e) {
        $.ajax({
            type: "GET",
            url: '/order-making',
            data: $(this).serialize(),
            success: function (data) {
                location.reload();
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
