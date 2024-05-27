@props(['text', 'icon'])

<div class="col-6 col-md-4">
    <a {{ $attributes->merge(['class' => 'text-decoration-none text-white', 'href' => '#', 'id' => 'addBtn']) }}>
        <div class="cart-place-btn">
            <i class="fa-solid {{ $icon }}"></i>
            <p>{{ $text }}</p>
        </div>
    </a>
</div>