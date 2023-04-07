<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{$product->name}}</title>
    <link rel="stylesheet" href="{{url('css/product.css')}}"/>
    <link href="{{url('bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
</head>
<body>

@include('components.header')

<main class="container d-flex flex-column gap-4 main mt-3">
    <div>
        <div class="align-items-center d-flex justify-content-between">
            <h3>{{$product->name}}</h3>
            <div class="dropdown">
                <button class="btn" type="button" data-bs-toggle="dropdown">
                    <img src="{{url('images\menu.svg')}}" alt="действия"/>
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenu2">
                    <li>
                        <button class="dropdown-item" type="button">Пожаловаться</button>
                    </li>
                    <li>
                        <button class="dropdown-item" type="button">Удалить</button>
                    </li>
                </ul>
            </div>
        </div>
        <div class="align-items-baseline d-flex elevation gap-1">
            @if(count($reviews) > 0)
                <p>{{$rating}}</p>
                <img class="star-image" src="{{url('images\star-filled.svg')}}" alt="звезда" />
                <div class="space"></div>
                <p>(оценок {{count($reviews)}})</p>
            @else
                <p>нет оценок</p>
            @endif
            <div class="space"></div>
            <div class="space"></div>
            <p>покупок {{$orderCount}}</p><a href="{{url('store/' . $storeId)}}">{{$storeName}}</a>
        </div>
    </div>
    <div class="d-flex">
        <div class="image-region">
            <img src="{{url($imagePath)}}" alt="Изображение товара" />
        </div>
        <div class="align-items-center d-flex description-region flex-column">
            <p class="price">{{$product->price}} руб</p>
            <button class="btn btn-warning fw-semibold text-dark add-to-cart-button" >Добавить в корзину</button>

            <p>{{$product->description}}</p>
        </div>
    </div>
    <div class="properties">
        <hr/>
        <h3>Характеристики</h3>
        <div class="d-flex flex-wrap list-unstyled">
            @for($i = 0; $i < count($properties); $i++)
                <dl>
                    <dt>{{$propertyNames[$i]}}</dt>
                    <dd>{{$properties[$i]->value}}</dd>
                </dl>
            @endfor
        </div>
    </div>
    <div>
        <h3>Похожее</h3>
        <div class="d-flex gap-2 justify-content-center overflow-auto p-3">
            @for($i = 0; $i < 0; $i++)
                @include('components.product_card', ['index' => $i])
            @endfor
        </div>
    </div>
    <div class="comments d-flex flex-column gap-4">
        <h3>Отзывы</h3>
        <button type="button" class="btn btn-warning open-popup-button open-popup-button_comment">ОСТАВИТЬ ОТЗЫВ
        </button>
        <div class="d-flex flex-column gap-4">
            @for($i = 0; $i < count($reviewsUsers); $i++)
                @include('components.comment', ['index' => $i])
            @endfor
        </div>
    </div>
</main>

@include('components.footer')

<aside class="popup-bg">
    @include('components.popups.authorization_popup')
    @include('components.popups.registration_popup')
    @include('components.popups.add_comment_popup')
</aside>
<script>
    $('.add-to-cart-button').click(function(e) {
        $.ajax({
            type: "POST",
            url: '/cart',
            data: {'productId': {{$product->id}}, '_token': '{{csrf_token()}}' },
            success: function (data) {
                alert("Товар добавлен в корзину!")
            },
            error: function (jqXHR, text, error) {
                alert(JSON.parse(jqXHR.responseText)['message']);
            },
        });
        return false;
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
</body>
</html>
