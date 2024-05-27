@props(['title', 'button', 'size' => 'md'])

<div class="modal fade" id="modal">
    <div class="modal-dialog modal-{{ $size }}">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ $title }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form {{ $attributes->merge(['method' => 'post', 'id' => 'submit']) }}>
                @csrf
                <div class="modal-body">
                    {{ $slot }}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <x-submit-button :text="$button" />
                </div>
            </form>
            
        </div>
    </div>
</div>