@props([
    'name',
    'url' => null,
    'startUrl',
    'items' => []
])

<li @class(['mm-active' => Request::is($startUrl)])>
    <a href="{{ $url != null ? $url : 'javascript:;'}}" @class(['has-arrow' => $url == null])>
        <div class="parent-icon">
            {{ $slot }}
        </div>
        <div class="menu-title">{{ $name }}</div>
    </a>
    @if (count($items) > 0)
        <ul>
            @foreach ($items as $item)
                @isset($item['can'])
                    @can($item['can']) <x-nav-link-item :item="$item" /> @endcan
                @else
                    <x-nav-link-item :item="$item" />
                @endisset
            @endforeach
        </ul>
    @endif
</li>
