<article class="border border-4 d-flex flex-column gap-2 product-card shadow-sm">
    <a href="{{url("/product/" . $data['product']->id)}}" >
        <img src="{{ url($data['imagePath']) }}" class="shadow-sm" alt="изображение продукта"/>
    </a>
    <span class="name"> {{ $data['product']->name }} </span>
    <span class="price"> {{ $data['product']->price . "р" }} </span>
</article>
