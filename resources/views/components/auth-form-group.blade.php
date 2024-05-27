@props([
    'name',
    'type' => 'text',
    'icon' => 'text',
    'placeholder' => null,
    'required' => true,
    'classes' => null,
    'value' => null,
])

<div class="form-group mb-3">
    <div class="icon" @if($type == 'password') id="changePassType" @endif>
        <i class="fa-solid {{ $icon}}"></i>
    </div>
    <input type="{{ $type }}" name="{{ $name }}" id="{{ $name }}" placeholder="{{ $placeholder }}" @if($required) required @endif @if($value != null) value="{{ $value }}" @endif class="form-control border-2 border-gray-100 rounded-43 py-2 px-4 {{ $classes }}" />
    <small class="pt-1 text-warning-600 error-message" id="invalid_{{ $name }}"></small>
</div>
