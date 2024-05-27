@props([
    'title',
    'value',
])
<div {{ $attributes->merge(['class' => 'dash_card']) }}>
    <div class="">
        <div class="dash_des">
            <div class="">
                <h6 class="des_title">{{ $title }}</h6>
            </div>
        </div>
        <h3 class="count">{{ $value }}</h3>
    </div>
</div>
