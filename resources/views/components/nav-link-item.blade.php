@props(['item'])

@if(isset($item['allows']))
    @if ($item['allows'])
        <li @class(['mm-active' => Request::is($item['startUrl'])])>
            <a href="{{ !isset($item['items']) != null ? $item['url'] : 'javascript:void(0)'}}"  @class(['has-arrow' => isset($item['items'])])>
                <i class="fa-solid fa-arrow-right"></i>
                {{ $item['name'] }}
            </a>
            @isset($item['items'])
                <ul>
                    @foreach ($item['items'] as $link)
                        <li @class(['mm-active' => Request::is($link['startUrl'])])>
                            <a href="{{ $link['url'] }}">
                                <i class="fa-solid fa-arrow-right"></i>
                                {{ $link['name'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endisset
        </li>
    @endif
@else
    <li @class(['mm-active' => Request::is($item['startUrl'])])>
        <a href="{{ !isset($item['items']) != null ? $item['url'] : 'javascript:void(0)'}}"  @class(['has-arrow' => isset($item['items'])])>
            <i class="fa-solid fa-arrow-right"></i>
            {{ $item['name'] }}
        </a>
        @isset($item['items'])
            <ul>
                @foreach ($item['items'] as $link)
                    <li @class(['mm-active' => Request::is($link['startUrl'])])>
                        <a href="{{ $link['url'] }}">
                            <i class="fa-solid fa-arrow-right"></i>
                            {{ $link['name'] }}
                        </a>
                    </li>
                @endforeach
            </ul>
        @endisset
    </li>
@endif

