<button type="button" class="btn active" data-id="" id="filterByCategory">
    <i class="fa-solid fa-star"></i>
    <span class="ms-1">All</span>
</button>
@foreach ($categories as $category)
    <button type="button" class="btn truncate" data-id="{{ $category->id }}" id="filterByCategory">
        <i class="fa-solid fa-star"></i>
        <span class="ms-1">{{ $category->name }}</span>
    </button>
@endforeach
