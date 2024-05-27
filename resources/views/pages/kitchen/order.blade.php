@php $unSeen = 0; @endphp

@forelse ($orders as $order)
    @php($running_time = orderRunningTime($order->seen_time))
    <div class="col-sm-6 col-md-4 col-xxl-3 mb-4">
        <div class="kitchen-card">
            <div class="kitchen-card-header fs-16">
                <div>
                    <p class="mb-0">Invoice: <span class="fw-normal">{{ $order->invoice }}</span></p>
                    <p class="mb-0">Table No:
                        <span class="fw-normal">
                            @forelse ($order->tables as $key => $table)
                                {{ $table->table?->number }} {{ !$loop->last ? ',':'' }}
                            @empty
                                N/A
                            @endforelse
                        </span>
                    </p>
                </div>
                <div>
                    <p class="mb-0" id="show_available_time">{{ gmdate('H:i:s', $running_time) }}</p>
                    <input type="hidden" id="available_time" value="{{ $running_time }}" />
                </div>

            </div>
            <div class="kitchen-card-body" id="kitchen-card-body">
                @foreach ($order->orderDetails as $orderDetails)
                    <div
                        @class([
                            'kitchen-order-item',
                            'order-cooking' => $orderDetails->status == $status->cooking,
                            'order-ready'   => $orderDetails->status == $status->ready,
                            'order-served'  => $orderDetails->status == $status->served,
                        ])
                        id="order-item" data-order-id="{{ $order->id }}"
                        data-item-id="{{ $orderDetails->id }}"
                        data-item-status="{{ $orderDetails->status }}"
                        data-selected="0"
                    >
                        <div class="d-flex justify-content-between mb-2">
                            <div class="order-item-details">
                                <p class="mb-0">{{ $orderDetails->food?->name }}</p>
                                <p class="item-quantity mb-0">
                                    <span>Qty: {{ $orderDetails->quantity }}</span>
                                    <span class="ms-2">Variant: {{ $orderDetails->variant?->name }}</span>
                                </p>
                                <div class="mt-2">
                                    @foreach ($orderDetails->addons as $orderAddonDetail)
                                        <div class="addon">
                                            <span class="name">{{ $orderAddonDetail->addon?->name }}</span>
                                            <span class="qty ms-1">{{ $orderAddonDetail->quantity }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div>
                                <div class="order-item-status">{{ $orderDetails->status }}</div>
                            </div>
                        </div>
                        <p class="mb-0 fs-12">{{ $orderDetails->note }}</p>
                    </div>
                @endforeach
            </div>
            <div class="kitchen-card-footer">
                <button type="button" class="btn" id="select-unselect" data-role="select">Select All</button>
                <button type="button" class="btn" id="select-unselect" data-role="unselect">Unselect All</button>
            </div>
        </div>
    </div>
@empty
    <div class="col-md-6 offset-md-3 mt-5 primary-text text-center">
        <div class="card border-0 primary-bg">
            <div class="card-body" id="order-items">
                <p class="mb-0 text-danger">Orders not available!!</p>
            </div>
        </div>
    </div>
@endforelse

<input type="hidden" id="unseen_order" value="{{ $unSeen }}" />
