@props([
    'name',
    'islabel' => true,
    'for' => null,
    'required' => true,
    'isType' => 'input',
    'column' => null,
    'question' => null,
])

@php
    $htmlFor = $for != null ? $for : $name;
    $attr    = $attributes->merge(['type' => 'text', 'class' => 'form-control', 'name' => $htmlFor, 'id' => $htmlFor]);
@endphp

<div @class([$column => $column != null, 'mb-3'])>
    @if ($islabel)
        <label for="{{ $htmlFor }}" class="form-label">
            {{ ucfirst(str_replace('_', ' ', $htmlFor)) }}
            @if ($required)
                <span class="text-primary-800">*</span>
            @endif
            @if ($question !== null)
                (<span class="text-primary-800">{{ $question }}</span>)
            @endif
        </label>
    @endif
    @if ($isType === 'input')
        <input {{ $attr }} @required($required ? true : false) />
    @elseif($isType === 'textarea')
        <textarea {{ $attr }} @required($required ? true : false)>{{ $slot }}</textarea>
    @elseif($isType === 'select')
        <select {{ $attr }} @required($required ? true : false)>
            {{ $slot }}
        </select>
    @else
        {{ $slot }}
    @endif

    <div class="invalid-feedback" id="invalid_{{ $htmlFor }}"></div>
</div>
