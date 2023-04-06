<article class="comment">
    <div class="d-flex gap-3">
        <img class="avatar" src="{{url($reviewsUsersImages[$i]->path . $reviewsUsersImages[$i]->name)}}"/>
        <div>
            <nav>{{$reviewsUsers[$i]->first_name . " " . $reviewsUsers[$i]->last_name}}</nav>
            <nav>{{(new DateTime($reviews[$i]->created_at))->format('Y-m-d')}}</nav>
            @for($k = 0; $k < $reviews[$i]->elevation; $k++)
                <img class="star-image" src="{{url('images\star-filled.svg')}}" alt="закрашеная звезда"/>
            @endfor
            @for($k = $reviews[$i]->elevation; $k < 5; $k++)
                <img class="star-image" src="{{url('images\star.svg')}}" alt="звезда"/>
            @endfor
        </div>
        <div class="dropdown">
            <button class="btn" type="button" data-bs-toggle="dropdown">
                <img src="{{url('images\menu.svg')}}"/>
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
    <p>{{$reviews[$i]->content}}</p>
</article>
