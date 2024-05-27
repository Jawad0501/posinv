@forelse ($orders as $order)
    <a class="dropdown-item" href="{{ route('pos.order.accept', $order->id) }}" id="acceptOrder">
        <div class="d-flex align-items-center">
            <div class="user-online">
                <img src="{{ uploaded_file($order->user->image) }}" class="msg-avatar" alt="user avatar">
            </div>
            <div class="flex-grow-1">
                <h6 class="msg-name">
                    {{ $order->invoice }}
                    <span class="msg-time float-end">{{ $order->created_at->diffForHumans() }}</span>
                </h6>
                <p class="msg-info">{{ convert_amount($order->grand_total) }}</p>
            </div>
        </div>
    </a>
    {{-- <div class="dropdown-item">
        <div class="d-flex align-items-center d-flex justify-content-between">
            <div class="flex-grow-1">
                <div class="align-items-center">
                    <p class="msg-info">Invoice : {{ $order->invoice }}</p>
                </div>
            </div>
            <div class="flex-grow-1">
                <div class="d-flex align-items-center justify-content-between">
                    <span class="msg-time">{{ convert_amount($order->grand_total) }}</span> 
                    <a id="acceptOrder" class="btn btn-sm btn-primary text-white"> 
                        <i class="fa fa-plus"></i> 
                    </a>
                </div>
            </div>
        </div>
    </div> --}}
@empty
    <div class="text-danger text-center py-2">Online order not available</div>
@endforelse