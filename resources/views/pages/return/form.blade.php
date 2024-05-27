@extends('layouts.app')

@section('title', isset($return) ? 'Update Product Return':'Add New Product Return')

@section('content')

    <div class="col-12 my-5">

        <x-page-card :title="isset($return) ? 'Update Product Return':'Add New Product Return'">

            <x-slot:cardButton>
                <x-card-heading-button :url="route('returns.return.index')" title="Back to Returns" icon="back" />
                @isset($return)
                    <x-card-heading-button :url="route('returns.return.show', $return->id)" title="Show Details" icon="show" />
                @endisset
            </x-slot>

            <form id="form" action="{{isset($return) ? route('returns.return.update', $return->id) : route('returns.return.store')}}" method="post" data-redirect="{{ route('returns.return.index') }}">
                @csrf
                @isset($return)
                    @method('PUT')
                @endisset

                <div class="card-body">

                <div class="mb-3">
                    <label for="choose_vender" class="form-label">Product Return From</label>
                    <select name="choose_vendor" id="choose_vendor" class="form-control">
                        <option value="">Select</option>
                        <option value="supplier">Supplier</option>
                        <option value="customer">Customer</option>
                    </select>
                </div>

                <input type="text" name="vendor" id="vendor" class="d-none"> 

                <div id="to_supplier" class="d-none row">
                    <x-form-group
                        name="supplier"
                        isType="select"
                        class="select2"
                        column="col-12 col-lg-6"
                        :required="false"
                    >
                        <option value="0">Select Supplier</option>
                        @foreach ($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" @isset($return) @selected($return->supplier_id == $supplier->id ? true:false) @endisset>{{ $supplier->name }}</option>
                        @endforeach
                    </x-form-group>

                    <div class="col-12 col-lg-6 form-group">
                        <label for="ref_number" class="form-label">Enter Invoice No</label>
                        <input type="text" class="form-control" name="ref_number" id="ref_number" placeholder="Enter Invoice No" value="{{ isset($return) ?  $return->invoice_no : 0 }}" />
                    </div>
                </div>

                <div id="from_customer" class="d-none row">
                    <x-form-group
                        name="customer"
                        isType="select"
                        class="select2"
                        column="col-12 col-lg-6"
                        :required="false"
                    >
                        <option value="0">Select Customer</option>
                        @foreach ($customers as $customer)
                            <option value="{{ $customer->id }}" @isset($return) @selected($return->client_id == $customer->id ? true:false) @endisset>{{ $customer->first_name }}</option>
                        @endforeach
                    </x-form-group>

                    <div class="col-12 col-lg-6 form-group">
                        <label for="invoice_no" class="form-label">Enter Invoice Number</label>
                        <input type="text" class="form-control" name="invoice_no" id="invoice_no" placeholder="Enter Invoice Number" value="{{ isset($return) ?  $return->return_amount : 0 }}" />
                    </div>
                </div>

                <div class="">
                    <div class="row">

                    <x-form-group
                            name="return_date"
                            class="datepicker"
                            :value="$return->return_date ?? date('Y-m-d')"
                            column="col-md-4"
                        />

                        <x-form-group
                            name="payment_type"
                            isType="select"
                            column="col-md-4"
                            class="select2"
                        >
                            <option value="">Select payment type</option>
                            @foreach (['cash payment', 'bank payment', 'due payment'] as $payment)
                                <option value="{{ $payment }}" @isset($purchase) @selected($purchase->payment_type == $payment ? true:false) @endisset>{{ ucfirst($payment) }}</option>
                            @endforeach
                        </x-form-group>

                        <div class="col-md-4 d-none" id="bank_area">
                            <x-form-group
                                name="bank"
                                isType="select"
                                :required="false"
                                class="select2"
                            >
                                <option value="">Select bank</option>
                                @foreach ($banks as $bank)
                                    <option value="{{ $bank->id }}" @isset($purchase) @selected($purchase->bank_id == $bank->id ? true:false) @endisset>{{ $bank->name }}</option>
                                @endforeach
                            </x-form-group>
                        </div>

                        <x-form-group
                            name="ingredient"
                            isType="select"
                            class="select2"
                            column="col-md-4"
                            :required="false"
                            :for="'product'"
                            id="ingredient"
                        >
                            <option value="">Select product</option>
                            @foreach ($ingredients as $ingredient)
                                <option value="{{ $ingredient->id }}" @isset($purchase) @selected($purchase->ingredient_id == $ingredient->id ? true:false) @endisset>{{ $ingredient->name }}({{ $ingredient->code }})</option>
                            @endforeach
                        </x-form-group>

                        <x-form-group
                            name="note"
                            for="details"
                            placeholder="Enter details"
                            :value="$purchase->details ?? ''"
                            column="col-md-12"
                            :required="false"
                        />

                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>SN</th>
                                        <th>Product</th>
                                        <th>Return Price</th>
                                        <th>Quantity/Amount</th>
                                        <th>Total</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>

                                <tbody id="items">
                                    @isset($purchase)
                                        @foreach ($purchase->items as $key => $item)
                                            <tr>
                                                <td>{{ $key + 1}}</td>
                                                <td>
                                                    {{ $item->ingredient->name }}
                                                    <input type="hidden" name="ingredient_id[]" id="ingredient_id" value="{{ $item->ingredient->id }}" />
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" name="unit_price[]" id="unit_price" placeholder="Unit Price" value="{{ $item->unit_price }}" />
                                                </td>
                                                <td class="d-flex justify-content-start align-items-center" id="quantity">
                                                    <input type="text" class="form-control" name="quantity_amount[]" id="quantity_amount" value="{{ $item->quantity_amount }}" placeholder="Unit Price" style="width:80%" />
                                                    <span class="ms-2">{{ $item->ingredient->unit->name }}</span>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" name="total[]" id="total" value="{{ $item->total }}" readonly />
                                                </td>
                                                <td>
                                                    <a href="{{ route('purchase.item.destroy', $item->id) }}" class="btn primary-text" id="removeIngredientItem"><i class='icofont-ui-delete'></i></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endisset
                                </tbody>

                                <tfoot>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td colspan="2">
                                            <x-form-group name="total_amount" :readonly="true" :value="$purchase->total_amount ?? 0" />
                                            <x-form-group name="shipping_charge" :value="$purchase->shipping_charge ?? 0" />
                                            <x-form-group name="discount_type" isType="select">
                                                <option value="fixed">Fixed</option>
                                                <option value="percentage">Percentage</option>
                                            </x-form-group>
                                            <x-form-group name="discount" :value="$purchase->discount_amount ?? 0" />
                                            <x-form-group name="grand_total" :readonly="true" :value="$purchase->grand_total ?? 0" />
                                            <x-form-group name="paid" :value="$purchase->paid_amount ?? 0" />
                                            <x-form-group name="due" :readonly="true" :value="$purchase->due_amount ?? 0" />
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    </div>

                </div>

                <div class="card-footer">
                    <x-submit-button :text="isset($return) ? 'Update':'Save'" />
                </div>
            </form>

            
        </x-page-card>
    </div>
@endsection

@push('js')

<script>

    $(function () {
        $(document).on('change', 'select#payment_type', function(e) {
            paymentTypeCheck(e.target.value)
        });

        paymentTypeCheck($('select#payment_type').val());

        function paymentTypeCheck(value) {
            if(value == 'bank payment') {
                $('#bank_area').removeClass('d-none');
            }
            else {
                $('#bank_area').addClass('d-none');
            }
        }

        $('#choose_vendor').on('change', function(event) {
            const selectedValue = event.target.value;

            // Reset the display of both fields
            $('#to_supplier').addClass('d-none');
            $('#from_customer').addClass('d-none');

            if (selectedValue === 'supplier') {
                $('#to_supplier').removeClass('d-none');
                $('#vendor').val('supplier');
            } else if (selectedValue === 'customer') {
                $('#from_customer').removeClass('d-none');
                $('#vendor').val('customer');
            }
        });
    });
   
</script>



@endpush

