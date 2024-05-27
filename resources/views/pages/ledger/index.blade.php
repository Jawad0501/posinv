@extends('layouts.app')

@section('title', 'User Ledger')

@push('css')
    <x-datatable.style />
@endpush

@section('content')

    <div class="col-12 my-5">

        <x-page-card title="Filter Data">

            <div class="card-body">

                <form action="/" method="GET" id="filterData">
                    <div class="row">
                        <x-form-group name="from" class="datepicker" placeholder="Start date" column="col-md-3" :islabel="false" :required="false" />
                        <x-form-group name="to" class="datepicker" placeholder="End date" column="col-md-3" :islabel="false"  :required="false"/>

                        <x-form-group name="customer" isType="select" class="select2" :islabel="false" column="col-md-3">
                            <option value="{{$first_customer->id}}" selected>{{ $first_customer->full_name }}</option>
                            @foreach ($customers as $customer)
                                @if ($customer->id !== $first_customer->id)
                                    <option value="{{ $customer->id }}">{{ $customer->full_name }}</option>
                                @endif
                            @endforeach
                        </x-form-group>

                        <div class="col-md-3">
                            <x-submit-button text="Search" :isReport="true" />
                        </div>
                    </div>
                </form>

            </div>

        </x-page-card>

        <div class="my-4"></div>

        <x-page-card title="Report">

            <x-slot:cardButton>
                <x-card-heading-button title="Print" icon="print" id="printData"/>
            </x-slot>

            <div class="card-body">
                <div class="card py-3 px-4">
                    <div>Name: <span id="customerName"></span></div>
                    <div>Phone: <span id="customerPhone"></span></div>
                    <div>Wallet Balance: <span id="customerWallet"></span></div>
                </div>
                <x-table :items="['SN', 'Date', 'Invoice', 'Total Items', 'Total', 'Paid', 'Change', 'Due']">
                    <x-slot name="tfoot">
                        <th colspan="3" class="text-end">Total:</th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </x-slot>
                </x-table>
            </div>

        </x-page-card>

    </div>

@endsection

@push('js')
    <x-datatable.script />

    <script>
        $('#printData').on('click', function(){
            var from = $('#from').val();
            var to = $('#to').val();
            var customer = $('#customer').val(); // Retrieve the selected value of customer

            $.ajax({
                url: '{!! route("ledgers.ledger.print") !!}',
                data: {
                    from: from,
                    to: to,
                    customer: customer // Pass the selected customer value
                },
                success: function (response){
                    var url = response.url;
                    
                    // Create a hidden link element
                    var link = document.createElement('a');
                    link.href = url;
                    link.style.display = 'none'; // Hide the link
                    link.download = 'Ledger-' + response.customer_full_name + '.pdf'; // Set the filename

                    // console.log(link);

                    // Add the link to the DOM
                    document.body.appendChild(link);

                    // Trigger a click event to start the download
                    link.click();

                    // Clean up: remove the link from the DOM
                    document.body.removeChild(link);
                },
                error: function(xhr, status, error) {
                    console.error('Error downloading PDF:', error);
                }
            });
        });

        $(document).ready(function() {
            $.ajax({
                url:  '{!! route("client.ledger.customer") !!}',
                data: {
                    customer_id: $('#customer').val()
                },
                success: function(response){
                    $('#customerName').html(response.customer.first_name); 
                    $('#customerPhone').html(response.customer.phone); 
                    $('#customerWallet').html(response.customer.wallet);
                }
            })
        })

        $(document).submit(function() {
            $.ajax({
                url:  '{!! route("client.ledger.customer") !!}',
                data: {
                    customer_id: $('#customer').val()
                },
                success: function(response){
                    $('#customerName').html(response.customer.first_name); 
                    $('#customerPhone').html(response.customer.phone); 
                    $('#customerWallet').html(response.customer.wallet);
                }
            })
        })
        
    </script>
@endpush
