@props(['text' => 'Submit', 'isReport' => false, 'type' => 'submit', 'onclick' => null])

<div @class(['text-end' => !$isReport])>
    <button {{ $attributes->merge(['type' => $type, 'data-text' => $text, 'class' => 'btn btn-primary px-5 text-white', 'onclick' => $onclick]) }}>
        <span class="spinner-border spinner-border-sm d-none" id="submit-spinner" role="status" aria-hidden="true"></span>
        {{ $text }}
    </button>
</div>
