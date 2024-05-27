@extends('layouts.app')

@section('title', 'Dashboard')

@push('css')
    <link href="{{ asset('/') }}build/assets/backend/plugins/vectormap/jquery-jvectormap-2.0.2.css" rel="stylesheet"/>
@endpush

@section('content')
    <div class="col-12">

        <div class="row mt-3">

            <div class="col-sm-6 col-lg-4 col-xxl-3 mb-4">
                <x-dashboard-card
                    title="Total Sale"
                    :value="$data['total_sale']"
                    class="dash_warning_card"
                />
            </div>

            <div class="col-sm-6 col-lg-4 col-xxl-3 mb-4">
                <x-dashboard-card
                    title="Total Received"
                    :value="$data['total_paid_sale']"
                    class="dash_warning_card"
                />
            </div>


            <div class="col-sm-6 col-lg-4 col-xxl-3 mb-4">
                <x-dashboard-card
                    title="Total Due"
                    :value="$data['total_due_sale']"
                    class="dash_warning_card"
                />
            </div>

            
            <div class="col-sm-6 col-lg-4 col-xxl-3 mb-4">
                <x-dashboard-card
                    title="Total Expense"
                    :value="$data['total_expense']"
                    class="dash_warning_card"
                />
            </div>

            <div class="col-sm-6 col-lg-4 col-xxl-3 mb-4">
                <x-dashboard-card
                    title="Product Wise Profit"
                    :value="$data['product_wise_profit']"
                    class="dash_warning_card"
                />
            </div>

            <div class="col-sm-6 col-lg-4 col-xxl-3 mb-4">
                <x-dashboard-card
                    title="Total Purchase"
                    :value="$data['total_purchase']"
                    class="dash_warning_card"
                />
            </div>

            <div class="col-sm-6 col-lg-4 col-xxl-3 mb-4">
                <x-dashboard-card
                    title="Total Payment"
                    :value="$data['total_payment']"
                    class="dash_warning_card"
                />
            </div>

            <div class="col-sm-6 col-lg-4 col-xxl-3 mb-4">
                <x-dashboard-card
                    title="Stock Product Value"
                    :value="$data['stock_product_value']"
                    class="dash_warning_card"
                />
            </div>

            <div class="col-sm-6 col-lg-4 col-xxl-3 mb-4">
                <x-dashboard-card
                    title="Nit Profit"
                    :value="$data['nit_profit']"
                    class="dash_warning_card"
                />
            </div>

        </div>
        

        <div class="row my-4">
            <div class="col-12">
                <div class="dash_table">
                    <a href="{{route('orders.order.index')}}" class="btn btn-primary text-white float-end">View All </a>

                    <div class="">
                        <p class="fs-30 fw-medium">Order History</p>

                        <div class="table-responsive">
                            <table class="table">
                                <tbody>
                                <tr class="font-secondary fs-16 text-gray-400">
                                    <th>ID</th>
                                    <th>Date & Time</th>
                                    <th>Customer Name</th>
                                    <th>Invoice</th>
                                    <th>Price</th>
                                    <th>Status</th>
                                </tr>
                                @foreach($data['order_history'] as $key => $order_his)
                                    <tr class="font-22 text-gray-800">
                                        <td>{{ $key+1 }}</td>
                                        <td>{{ $order_his->created_at->format('d-m-Y') }}</td>
                                        <td>{{ $order_his->user?->full_name ?? null }}</td>
                                        <td>{{ $order_his->invoice }}</td>
                                        <td>{{ convert_amount($order_his->grand_total) }}</td>
                                        <td>{{ $order_his->status }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end row-->

    </div>

@endsection

@push('js')
    <script src="{{ asset('/') }}build/assets/backend/plugins/vectormap/jquery-jvectormap-2.0.2.min.js"></script>
    <script src="{{ asset('/') }}build/assets/backend/plugins/vectormap/jquery-jvectormap-world-mill-en.js"></script>
    <script src="{{ asset('/') }}build/assets/backend/plugins/chartjs/js/Chart.min.js"></script>
    <script src="{{ asset('/') }}build/assets/backend/plugins/chartjs/js/Chart.extension.js"></script>
    {{-- <script src="{{ asset('/') }}build/assets/backend/js/index.js"></script> --}}
    <script>
        $(document).ready(function () {
            var ctx = document.getElementById("chart1").getContext('2d');

            var gradientStroke1 = ctx.createLinearGradient(0, 0, 0, 300);
            gradientStroke1.addColorStop(0, '#6078ea');
            gradientStroke1.addColorStop(1, '#17c5ea');

            var gradientStroke2 = ctx.createLinearGradient(0, 0, 0, 300);
            gradientStroke2.addColorStop(0, '#ff8359');
            gradientStroke2.addColorStop(1, '#ffdf40');

            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    datasets: [
                        {
                            label: 'Foods',
                            data: @json($data['sales']),
                            borderColor: gradientStroke1,
                            backgroundColor: gradientStroke1,
                            hoverBackgroundColor: gradientStroke1,
                            pointRadius: 0,
                            fill: false,
                            borderWidth: 0
                        },
                        {
                            label: 'Mobiles',
                            data: [28, 48, 40, 19, 28, 48, 40, 19, 40, 19, 28, 48],
                            borderColor: gradientStroke2,
                            backgroundColor: gradientStroke2,
                            hoverBackgroundColor: gradientStroke2,
                            pointRadius: 0,
                            fill: false,
                            borderWidth: 0
                        }
                    ]
                },
                options: {
                    maintainAspectRatio: false,
                    legend: {
                        position: 'bottom',
                        display: false,
                        labels: {
                            boxWidth: 8
                        }
                    },
                    tooltips: {
                        displayColors: false,
                    },
                    scales: {
                        xAxes: [{
                            barPercentage: .5
                        }]
                    }
                }
            });


            var ctx = document.getElementById("chart2").getContext('2d');

            var gradientStroke1 = ctx.createLinearGradient(0, 0, 0, 300);
                gradientStroke1.addColorStop(0, '#ED7402');
                gradientStroke1.addColorStop(1, '#f7b733');

            var gradientStroke2 = ctx.createLinearGradient(0, 0, 0, 300);
                gradientStroke2.addColorStop(0, '#00A600');
                gradientStroke2.addColorStop(1, '#8e54e9');


            var gradientStroke3 = ctx.createLinearGradient(0, 0, 0, 300);
            gradientStroke3.addColorStop(0, '#ED0206');
            gradientStroke3.addColorStop(1, '#ff6a00');

            var gradientStroke4 = ctx.createLinearGradient(0, 0, 0, 300);
            gradientStroke4.addColorStop(0, '#096ADB');
            gradientStroke4.addColorStop(1, '#3bb2b8');

            var myChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: @json($data['types']),
                    datasets: [{
                        backgroundColor: [
                            gradientStroke1,
                            gradientStroke2,
                            gradientStroke3,
                            gradientStroke4
                        ],
                        hoverBackgroundColor: [
                            gradientStroke1,
                            gradientStroke2,
                            gradientStroke3,
                            gradientStroke4
                        ],
                        data: @json($data['type_sales']),
                        borderWidth: [1, 1, 1, 1]
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    cutoutPercentage: 75,
                    legend: {
                        position: 'bottom',
                        display: false,
                        labels: {
                            boxWidth:8
                        }
                    },
                    tooltips: {
                        displayColors:false,
                    }
                }
            });
        });
    </script>
@endpush
