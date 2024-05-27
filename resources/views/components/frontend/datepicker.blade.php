@props(['options' => []])

@php
    $options = array_merge(['dateFormat' => 'M d, Y','minDate' => 'today','altInput' => true], $options);
@endphp

<div wire:ignore>
    <input
        x-data="{
           init() {
               flatpickr(this.$refs.input, {{json_encode((object)$options)}});
           }
        }"
        x-ref="input"
        type="text"
        placeholder="{{ date('M d, Y') }}"
        {{ $attributes->merge(['class' => 'form-input w-full rounded-md shadow-sm']) }}
    />
</div>
