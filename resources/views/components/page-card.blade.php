@props([
    'title' => null,
    'btnTitle' => null,
    'route' => null,
    'addBtn' => false
])

<div {{ $attributes->merge(['class' => 'card page-card']) }}>

    @if ($title != null || $btnTitle != null)
        <div class="card-header p-4 d-flex justify-content-between">
            <h3 class="card-title mb-0 fs-3">{{ $title ?? '' }}</h3>
            
            @if ($btnTitle != null)
                <a href="{{ $route != null ? route($route):'#' }}" class="btn primary-btn" @if($addBtn) id="addBtn" @endif>{{ $btnTitle }}</a>
            @else
                <div>{{ $cardButton ?? '' }}</div>
            @endif
        </div>
    @endif

    {{ $slot }}

</div>