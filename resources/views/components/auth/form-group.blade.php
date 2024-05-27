@props(['name', 'for' => null, 'type' => 'text', 'type' => 'text', 'placeholder', 'value' => null, 'required' => true])
<div class="mb-3">
    <label for="{{ $for != null ? $for : $name }}"
        class="form-label text-capitalize">{{ str_replace('_', '', $name) }}</label>
    <input type="{{ $type }}" class="form-control" name="{{ $name }}" id="{{ $name }}"
        placeholder="{{ $placeholder }}" @if ($value != null) value="{{ $value }}" @endif @required($required) />
    <div class="invalid-feedback" id="invalid_{{ $name }}"></div>
</div>
