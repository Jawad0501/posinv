@props(['pagination', 'total'])

@php
    function pageLink($page) {
        return url()->current().'?page='.$page;
    }
    $page      = $pagination->page;
    $pageCount = $pagination->pageCount;
    $itemCount = $pagination->itemCount;
@endphp

<nav aria-label="..." class="pagination justify-content-between mt-3">
    <div>Showing 1 to {{ $total }} of {{ $itemCount }} entries</div>
    <ul class="pagination mb-0">
        <li @class(['page-item', 'disabled' => $page <= 1])>
            <a class="page-link" href="{{ $page > 1 ? pageLink($page - 1) : '#' }}" id="getData">
                Previous
            </a>
        </li>
        @for ($i = 1; $i <= $pageCount; $i++)
            <li @class(['page-item', 'active' => $page == $i]) @if ($page == $i) aria-current="page" @endif>
                <a class="page-link" href="{{ pageLink($i) }}" id="getData">{{ $i }}</a>
            </li>
        @endfor
        <li @class(['page-item', 'disabled' => $page == $pageCount])>
            <a class="page-link" href="{{ $page != $pageCount ? pageLink($page + 1) : '#' }}" id="getData">
                Next
            </a>
        </li>
    </ul>
</nav>