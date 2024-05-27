@extends('layouts.app')

@section('title', 'Supplier Ledger')

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

                        <x-form-group name="supplier" isType="select" class="select2" :islabel="false" column="col-md-3">
                            <option value="{{$first_supplier->id}}" selected>{{ $first_supplier->name }}</option>
                            @foreach ($suppliers as $supplier)
                                @if ($supplier->id !== $first_supplier->id)
                                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
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
                    <div>Name: <span id="supplierName"></span></div>
                    <div>Phone: <span id="supplierPhone"></span></div>
                    <div>Advance Paid Balance: <span id="supplierWallet"></span></div>
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
        // $('#printData').on('click', function(){
        //     var from = $('#from').val();
        //     var to = $('#to').val();
        //     var supplier = $('#supplier').val(); // Retrieve the selected value of supplier


        //     $.ajax({
        //         url: '{!! route("ledgers.ledger.print") !!}',
        //         data: {
        //             from: from,
        //             to: to,
        //             customer: supplier // Pass the selected customer value
        //         },
        //         success: function (response){
        //             var url = response.url;
                    
        //             // Create a hidden link element
        //             var link = document.createElement('a');
        //             link.href = url;
        //             link.style.display = 'none'; // Hide the link
        //             link.download = 'Ledger-' + customer + '.pdf'; // Set the filename

        //             // Add the link to the DOM
        //             document.body.appendChild(link);

        //             // Trigger a click event to start the download
        //             link.click();

        //             // Clean up: remove the link from the DOM
        //             document.body.removeChild(link);
        //         },
        //         error: function(xhr, status, error) {
        //             console.error('Error downloading PDF:', error);
        //         }
        //     });
        // });

        $(document).ready(function() {
            $.ajax({
                url:  '{!! route("ledger.supplier") !!}',
                data: {
                    supplier_id: $('#supplier').val()
                },
                success: function(response){
                    $('#supplierName').html(response.supplier.name); 
                    $('#supplierPhone').html(response.supplier.phone); 
                    $('#supplierWallet').html(response.supplier.wallet);
                }
            })
        })

        $(document).submit(function() {
            $.ajax({
                url:  '{!! route("ledger.supplier") !!}',
                data: {
                    supplier_id: $('#supplier').val()
                },
                success: function(response){
                    $('#supplierName').html(response.supplier.name); 
                    $('#supplierPhone').html(response.supplier.phone); 
                    $('#supplierWallet').html(response.supplier.advance_amount);
                }
            })
        })
        
    </script>
@endpush
