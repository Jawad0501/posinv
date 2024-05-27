@props(['text' => 'Submit'])

<div class="mb-3 text-end">
    <button class="btn btn-login" type="submit" data-text="{{ $text }}">
        <span class="spinner-border spinner-border-sm d-none" id="submit-spinner" role="status" aria-hidden="true"></span>
        {{ $text }}
    </button>
</div>