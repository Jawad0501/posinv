@props(['title', 'button' => null, 'size' => 'md', 'button_type' => 'submit', 'on_click' => null])

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


                @if ($button != null)
                    <div class="modal-footer">
                        @isset($extra)
                            {{ $extra }}
                        @else
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        @endisset

                        <x-submit-button :text="$button" :type="$button_type" :onclick="$on_click" />
                    </div>
                @endif
            </form>

        </div>
    </div>
</div>
