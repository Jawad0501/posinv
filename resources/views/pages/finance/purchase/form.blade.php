@extends('layouts.app')

@section('title', isset($purchase) ? 'Update Purchase':'Add New Purchase')

@section('content')

    <div class="col-12 my-5">

        <x-page-card :title="isset($purchase) ? 'Update Purchase':'Add New Purchase'">

            <x-slot:cardButton>
                <x-card-heading-button :url="route('purchase.index')" title="Back to Purchase" icon="back" />
                @isset($purchase)
                    <x-card-heading-button :url="route('purchase.show', $purchase->id)" title="Show Details" icon="show" />
                @endisset
            </x-slot>

            <form id="form" action="{{isset($purchase) ? route('purchase.update', $purchase->id) : route('purchase.store')}}" method="post" data-redirect="{{ route('purchase.index') }}">
                @csrf
                @isset($purchase)
                    @method('PUT')
                @endisset

                <div class="card-body">

                    <div class="row">

                        <div class="col-md-4">
                            <div class="row align-items-center">
                                <x-form-group
                                    name="supplier"
                                    isType="select"
                                    class="select2"
                                    column="col-10"
                                >
                                    <option value="">Select Supplier</option>
                                    @foreach ($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}" @isset($purchase) @selected($purchase->supplier_id == $supplier->id ? true:false) @endisset>{{ $supplier->name }}</option>
                                    @endforeach
                                </x-form-group>
                                <div class="col-2 mt-3">
                                    <a href="{{ route('supplier.create') }}" class="btn" id="addBtn"><i class="icofont-plus"></i></a>
                                </div>
                            </div>
                        </div>

                        <x-form-group
                            name="purchase_date"
                            class="datepicker"
                            :value="$purchase->date ?? date('Y-m-d')"
                            column="col-md-4"
                        />

                        <x-form-group
                            name="payment_type"
                            isType="select"
                            column="col-md-4"
                            class="select2"
                        >
                            <option value="">Select payment type</option>
                            @foreach (['cash payment', 'due payment'] as $payment)
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
                                        <th>Unit Price</th>
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
                                            <div class="d-flex align-items-center my-2">
                                                <input type="checkbox" class="me-2" id="settle_advance" name="settle_advance" @isset($purchase) @checked($purchase->settled_from_advance == true ? true : false) @endisset/>
                                                <label for="settle_advance">Settle From Advance (Advance Paid: <span id="supplier_advance_amount"></span> )</label>
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <div class="me-2">
                                                    Cash In Hand:
                                                </div>
                                                <div>
                                                    {{ setting('cash_in_hand') }}
                                                </div>
                                            </div>
                                            <x-form-group name="paid" :value="$purchase->paid_amount ?? 0" />
                                            <x-form-group name="change" :value="$purchase->change_amount ?? 0" />
                                            <div class="d-flex align-items-center">
                                                <input type="checkbox" class="me-2" name="change_returned" id="change_returned" @isset($purchase) @checked($purchase->change_returned == true ? true : false) @endisset />
                                                <label for="change_returned">Change Returned</label>
                                            </div>
                                            <x-form-group name="due" :readonly="true" :value="$purchase->due_amount ?? 0" />
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                </div>
                <div class="card-footer">
                    <x-submit-button :text="isset($purchase) ? 'Update':'Save'" />
                </div>
            </form>

            {{-- This input use for add supplier after get all suppliers --}}
            <input type="hidden" id="get_select2_data" value="supplier" data-selected="{{ true }}" data-url="{{ route('purchase.supplier') }}"  />

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
        });

        $('#supplier').on('change', function(e){
            var advanceRoute = "{{ route('purchase.supplier.advance', ':supplierId') }}";
            advanceRoute = advanceRoute.replace(':supplierId', e.target.value);
            $.ajax({
                type: 'GET',
                url: advanceRoute,
                dataType: 'JSON',
                success: function (response) {
                    console.log(parseInt(response.supplier_advance_amount))
                    $('#supplier_advance_amount').html(parseInt(response.supplier_advance_amount));
                },
                error: function (e) {
                    handleError(e)
                }
            })
        })
        
        $('#settle_advance').on('change', function(e) {
            if(e.target.checked == true){
                if($('#supplier').val() > 0){
                    var grand_total = $('#grand_total').val();
                    if(grand_total == 0){
                        console.log($('#settle_advance').checked)
                        $('#settle_advance').prop('checked', false);
                        var error = { status: 'crash', statusText: 'Select Items To Purchase First' };
                        return handleError(error);
                    }

                    var advanceRoute = "{{ route('purchase.supplier.advance', ':supplierId') }}";
                    advanceRoute = advanceRoute.replace(':supplierId', $('#supplier').val());
                    $.ajax({
                        type: 'GET',
                        url: advanceRoute,
                        dataType: 'JSON',
                        success: function (response) {
                            if(response.supplier_advance_amount > parseInt(grand_total)){
                                $('#paid').val(grand_total);
                                $('#paid').prop('readonly', true);
                                $('#due').val(0);
                            }
                            else if(response.supplier_advance_amount < parseInt(grand_total)){
                                $('#settle_advance').prop('checked', false);
                                var error = { status: 'crash', statusText: 'Insufficient Advance Amount Paid' };
                                return handleError(error)
                            }
                        },
                        error: function (e) {
                            handleError(e)
                        }
                    })
                }
                else{
                    $('#settle_advance').prop('checked', false);
                    var error = { status: 'crash', statusText: 'Select Supplier First' };
                    handleError(error);
                }
                
                
            }
            else{
                $('#paid').prop('readonly', false);
            }
        })
    </script>
@endpush
