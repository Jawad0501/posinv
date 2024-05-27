@extends('layouts.app')

@section('title', 'Sales details')

@section('content')

    <div class="col-12 my-5">
        <x-page-card title="Sales details">
            <x-slot:cardButton>
                <x-card-heading-button :url="route('orders.order.index')" title="Back to order" icon="back" />
            </x-slot>

            <div class="card-body mt-3">
                <div class="row mt-4">
                    <div class="col-12">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th width="15%">Invoice</th>
                                    <td>{{ $order->invoice }}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        <span class="badge bg-primary-800">{{ ucfirst($order->status) }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Order By</th>
                                    <td>{{ $order->order_by }}</td>
                                </tr>
                                <tr>
                                    <th>Discount</th>
                                    <td>{{ convert_amount($order->discount) }}</td>
                                </tr>
                                <tr>
                                    <th>Service Charge</th>
                                    <td>{{ convert_amount($order->service_charge) }}</td>
                                </tr>
                                <tr>
                                    <th>Delivery Charge</th>
                                    <td>{{ convert_amount($order->delivery_charge) }}</td>
                                </tr>
                                <tr>
                                    <th>Grand Total</th>
                                    <td>{{ convert_amount($order->grand_total) }}</td>
                                </tr>
                                <tr>
                                    <th>Note</th>
                                    <td>{{ $order->note }}</td>
                                </tr>
                                <tr>
                                    <th>Token Number</th>
                                    <td>{{ $order->rewards }}</td>
                                </tr>
                            </tbody>
                        </table>

                        @if ($order->user_id != null)
                            <div class="mt-3">
                                <h3 class="fs-3 text-center">Customer Information</h3>
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <th width="15%">Name</th>
                                            <td>{{ $order->user->full_name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Email</th>
                                            <td>{{ $order->user->email }}</td>
                                        </tr>
                                        <tr>
                                            <th>Phone</th>
                                            <td>{{ $order->user->phone }}</td>
                                        </tr>
                                        <tr>
                                            <th>Delivery Address</th>
                                            <td>{{ $order->user->delivery_address }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        @endif

                        @if ($order->payment != null)
                            <div class="mt-3">
                                <h3 class="fs-3 text-center">Payment Information</h3>
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <th width="15%">Method</th>
                                            <td>{{ $order->payment->method }}</td>
                                        </tr>
                                        <tr>
                                            <th>Give Amount</th>
                                            <td>{{ convert_amount($order->payment->give_amount) }}</td>
                                        </tr>
                                        <tr>
                                            <th>Change Amount</th>
                                            <td>{{ convert_amount($order->payment->change_amount) }}</td>
                                        </tr>
                                        <tr>
                                            <th>Grand Total</th>
                                            <td>{{ convert_amount($order->payment->grand_total) }}</td>
                                        </tr>
                                        <tr>
                                            <th>Due Amount</th>
                                            <td>{{ convert_amount($order->payment->due_amount) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        @endif

                        <div class="mt-3">
                            <h3 class="fs-3 text-center">Sales Summary</h3>
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th>SN</th>
                                        <th>Product Name</th>
                                        <th>Purchase Lot</th>
                                        <th>Purchase Price</th>
                                        <th>Sell Price</th>
                                        <th>Margin</th>
                                        <th>Qty</th>
                                        <th>Total Price</th>
                                    </tr>
                                    @foreach ($order->orderDetails as $orderDetails)
                                    @php 

                                        $item = $orderDetails->purchase;

                                        if($item->unit_price < $orderDetails->price) {
                                            $profit = true;
                                            $profit_amount = ($orderDetails->price - $item->unit_price) * $orderDetails->quantity;
                                        }
                                        else{
                                            $profit = false;
                                            $loss_amount = ($item->unit_price - $orderDetails->price) * $orderDetails->quantity;
                                        }

                                        $ingredient = App\Models\Ingredient::where('id', $orderDetails->food_id)->first();
                                    @endphp
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td> 
                                               {{$ingredient->name}}
                                            </td>
                                            <td>{{ $item->purchase_id }}</td>
                                            <td> {{ $item->unit_price }} </td>
                                            <td>{{ convert_amount($orderDetails->price) }}</td>
                                            <td>
                                                @php
                                                    $output = $profit ? $profit_amount . ' <span class="text-green">(Profit)</span>' : $loss_amount . ' <span class="text-red">(Loss)</span>';
                                                @endphp
                                                {!! $output !!}
                                            </td>
                                            <td>{{ $orderDetails->quantity }}</td>
                                            <td>{{ convert_amount($orderDetails->total_price) }}</td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <th colspan="4" class="text-end">Grand Total:</th>
                                        <th>{{ convert_amount($order->orderDetails->sum('price')) }}</th>
                                        <th></th>
                                        <th>{{ $order->orderDetails->sum('quantity') }}</th>
                                        <th>{{ convert_amount($order->orderDetails->sum('total_price')) }}</th>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>

            </div>

        </x-page-card>
    </div>
@endsection
