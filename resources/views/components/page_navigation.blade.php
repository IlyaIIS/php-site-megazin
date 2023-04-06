<nav calss="page-navigation" aria-label="Page navigation">
    <ul class="pagination">
        <li class="page-item">
            <a class="page-link" href="{{ url()->current() . "?page=" . 1 }}">&LeftArrowBar;</a>
        </li>
        @if($pageNum > 1)
            <li class="page-item">
                <a class="page-link" href="{{ url()->current() . "?page=" . ($pageNum - 1) }}">{{ $pageNum - 1 }}</a>
            </li>
        @endif
        <li class="page-item">
            <a class="page-link choosed">{{ $pageNum }}</a>
        </li>
        @if($pageNum < $pageCount)
            <li class="page-item">
                <a class="page-link" href="{{ url()->current() . "?page=" . ($pageNum + 1) }}">{{ $pageNum + 1 }}</a>
            </li>
        @endif
        <li class="page-item">
            <a class="page-link" href="{{ url()->current() . "?page=" . $pageCount }}">&RightArrowBar;</a>
        </li>
    </ul>
</nav>
