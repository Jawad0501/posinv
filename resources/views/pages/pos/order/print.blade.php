<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" type="image/x-icon" href="{{ uploaded_file(setting('favicon')) }}">

    <title>Order invoice - {{ config('app.name') }}</title>

    <!-- Google font -->

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    @vite(['resources/css/backend.css','resources/sass/backend/app.scss'])

</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3 col-sm-12 col-lg-3 mx-auto bg-white">
                <div class="text-center p-2 fs-24">
                    JUICE AND TREATS
                </div>

                <div class="fs-14 text-center">
                    <div class="d-flex justify-content-center p-2">
                        <p><strong>Order Number: </strong>{{ $order->rewards }}</p>
                    </div>
                </div>

                <div class="fs-12 p-3">
                    <p class="text-center fs-18 fw-bolder text-gray"><strong> Order Summary </strong></p>
                    <table class="table order_detail_table">
                        <thead>
                            <tr>
                                <th scope="col">SL</th>
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
                                                    {{ $orderAddonDetails->addon->name }}
                                                    <span>{{ $orderAddonDetails->quantity }}x{{ convert_amount($orderAddonDetails->price) }}</span>
                                                </a>
                                            @endforeach
                                        </td>
                                    </tr>
                                @endif
                            @empty
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <hr>

                <div class="fs-12">
                    <div class="d-flex justify-content-between">
                        <div class="fs-13">Order Type:</div>
                        <div>{{ $order->type }}</div>
                    </div>
                </div>

                <div class="fs-12">
                    <div class="d-flex justify-content-between">
                        <div class="fs-13">Subtotal:</div>
                        <div>{{ convert_amount($order->orderDetails->sum('total_price')) }}</div>
                    </div>
                </div>
                <div class="fs-12">
                    <div class="d-flex justify-content-between">
                        <div class="fs-13">Discount:</div>
                        <div>{{ convert_amount($order->discount) }}</div>
                    </div>
                </div>
                <div class="fs-12">
                    <div class="d-flex justify-content-between">
                        <div class="fs-13">Vat:</div>
                        <div class="order-menu-price">{{ $order->orderDetails->sum('vat') }}%</div>
                    </div>
                </div>
                {{-- <div class="fs-12">
                    <div class="d-flex justify-content-between">
                        <div class="fs-13">Service:</div>
                        <div>{{ convert_amount($order->service_charge) }}</div>
                    </div>
                </div>
                <div class="fs-12">
                    <div class="d-flex justify-content-between">
                        <div class="fs-13">Give Amount:</div>
                        <div>{{ convert_amount($order->payment->give_amount ?? 0) }}</div>
                    </div>
                </div>
                <div class="fs-12">
                    <div class="d-flex justify-content-between">
                        <div class="fs-13">Change Amount:</div>
                        <div>{{ convert_amount($order->payment->change_amount ?? 0) }}</div>
                    </div>
                </div>

                <div class="fs-12">
                    <div class="d-flex justify-content-between">
                        <div class="fs-13">Payment Method:</div>
                        <div>{{ $order->payment->method ?? 'Not Payment' }}</div>
                    </div>
                </div>

                <div class="fs-12">
                    <div class="d-flex justify-content-between">
                        <div>Delivery Charge:</div>
                        <div>{{ convert_amount($order->delivery_charge) }}</div>
                    </div>
                </div> --}}
                <hr>
                <div class="text-center fw-bold">
                    <p class="fs-16 mb-0">Paid Amount</p>
                    <h3 class="fs-3">{{ convert_amount($order->grand_total) }}</h3>
                </div>
                <p class="fs-16 fw-bold text-center">Thanks for ordering.</p>
                <div class="text-end mb-3">
                    <x-card-heading-button title="Print" icon="print" class="d-print-none" onclick="window.print()" />
                </div>
            </div>
        </div>
    </div>

    @vite('resources/js/backend/app.js')
</body>

</html>
