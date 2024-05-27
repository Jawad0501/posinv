@props(['text'])

<li class="nav-item">
    <a {{ $attributes->merge(['class' => 'tab-btn', 'href' => '#', 'data-type' => $text]) }}>
        <div class="tab-image">
            {{ $slot }}
        </div>
        <p>{{ strtoupper($text) }}</p>
    </a>
</li>