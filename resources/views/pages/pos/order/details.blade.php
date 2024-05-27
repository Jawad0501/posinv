<div class="modal fade" id="modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Sales Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="p-2 fs-12">
                    <div class="d-flex justify-content-between">
                        <!-- <p class="w-25 mb-0"><strong>Order Type: </strong>{{ $order->type }}</p> -->
                        {{-- <p class="w-25 mb-0"> <strong>Customer Name: </strong>{{ $order->user->full_name ?? $order->user->customer_id }}</p> --}}
                        <p class="w-25 mb-0"> <strong>Invoice: </strong>{{ $order->invoice }}</p>
                    </div>
                    <div class="d-flex justify-content-between">
                        <p class="w-25 mb-0"> <strong>Table Number:</strong>
                            @forelse ($order->tables as $key => $table)
                                {{ $table->table?->number }} {{ !$loop->last ? ',':'' }}
                            @empty
                                N/A
                            @endforelse
                        </p>
                        {{-- <p class="w-25 mb-0"><strong>Processing Time: </strong>{{ $order->processing_time }}</p> --}}
                        <p class="w-25 mb-0"> <strong>Price: </strong>{{ convert_amount($order->grand_total) }}</p>
                    </div>
                </div>

                <table class="table order_detail_table">
                    <thead>
                        <tr>
                            <th scope="col">SL</th>
                            @if (request()->has('kitchen'))
                                <th scope="col">Status</th>
                            @endif
                            <th scope="col">Name</th>
                            <th scope="col">Variant Name</th>
                            <th scope="col">Price</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Vat</th>
                            <th scope="col">Total</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($order->orderDetails as $key => $orderDetails)
                            <tr>
                                <td scope="row">{{ $key + 1 }}</td>
                                @if (request()->has('kitchen'))
                                    <td>
                                        <span class="badge bg-primary-800 rounded-6">{{ $orderDetails->status }}</span>
                                    </td>
                                @endif
                                <td>{{ $orderDetails->food?->name }}</td>
                                <td>{{ $orderDetails->variant?->name }}</td>
                                <td>{{ convert_amount($orderDetails->price) }}</td>
                                <td>{{ $orderDetails->quantity }}</td>
                                <td>{{ $orderDetails->vat }} %</td>
                                <td>{{ convert_amount($orderDetails->total_price) }}</td>
                            </tr>
                            @if ($orderDetails->addons->count() > 0)
                                <tr>
                                    <td colspan="7">
                                        @foreach ($orderDetails->addons as $orderAddonDetails)
                                            <a href="#" class="misc-item">
                                                {{ $orderAddonDetails->addon?->name }}
                                                <span>{{ $orderAddonDetails->quantity }}x{{ convert_amount($orderAddonDetails->price) }}</span>
                                            </a>
                                        @endforeach
                                    </td>
                                </tr>
                            @endif
                            @if ($orderDetails->note !== null)
                                <tr>
                                    <td colspan="7">
                                        <strong>Note:</strong> {{ $orderDetails->note }}
                                    </td>
                                </tr>
                            @endif
                        @empty
                        @endforelse
                    </tbody>
                </table>

                <div class="p-2 fs-12">
                    <div class="d-flex justify-content-between">
                        <p class="w-25 mb-0"> <strong>Total Item: </strong>{{ $order->orderDetails->count() }}</p>
                        <p class="w-25 mb-0"> <strong>Sub Total: </strong>{{ convert_amount($order->orderDetails->sum('total_price')) }}</p>
                        <p class="w-25 mb-0"> <strong>Service Charge: </strong>{{ convert_amount($order->service_charge) }}</p>
                    </div>
                    <div class="d-flex justify-content-between">
                        <p class="w-25 mb-0"> <strong>Delivery Charge: </strong>{{ convert_amount($order->delivery_charge) }}</p>
                        <p class="w-25 mb-0"> <strong>VAT: </strong>{{ $order->orderDetails->sum('vat') }}%</p>
                        <p class="w-25 mb-0"> <strong>Discount: </strong>{{ convert_amount($order->discount) }}</p>
                    </div>
                </div>
                <div class="p-2">
                    <p class="mb-0 text-center"> Grand Total: {{ convert_amount($order->grand_total) }} </p>
                </div>
            </div>

            {{-- @if (!request()->has('kitchen'))
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <a href="{{ route('pos.payment.create') }}?order_id={{ $order->id }}" id="addBtn" class="btn btn-primary px-5 text-white">Create Invoice</a>
                </div>
            @endif --}}
        </div>
    </div>
</div>
