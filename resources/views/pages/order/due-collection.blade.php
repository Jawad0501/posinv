<x-form-modal
    :title="'Due Collection Under Invoice No '.$order->invoice"
    action="{{ route('pos.order.due-collection.store', $order->id)}}"
    :button="'Submit'"
    id="form"
    size="lg"
>

    <div class="row">

    <input type="hidden" name="customer_id" id="customer_id" value="{{$order->user_id}}">
    <input type="hidden" name="payable_amount_hidden" id="payable_amount_hidden" value="{{$order->grand_total}}">
    <input type="hidden" name="give_amount_hidden" id="give_amount_hidden" value="{{$order->payment?->give_amount ? $order->payment->give_amount : 0}}">
    <input type="hidden" name="order_id" id="order_id" value="{{$order->id}}">

        <!-- <x-form-group name="name" placeholder="Name" :value="$ingredient->name ?? ''" column="col-md-6" /> -->
        <div class="row">
            <div class="col-12">
                <div class="d-flex align-items-center">
                    <div>Order Total:</div>
                    <div>{{$order->grand_total}}</div>
                </div>
            </div>
            <div class="col-12">
                <div class="d-flex align-items-center">
                    <div>Total Amount Paid Till Today:</div>
                    <div>{{$order->payment->give_amount}}</div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <div>
                    <input type="checkbox" name="settle_advance" id="settle_advance" />
                    <label for="settle_advance">Settle From Customer Wallet (Wallet Balance: {{$order->user->wallet}} )</label>
                </div>
            </div>
        </div>

        <x-form-group
            name="give_amount"
            placeholder="Give Amount"
            column="col-md-12"
        />
        
        <x-form-group
            name="change_amount"
            placeholder="Change_Amount"
            column="col-md-12"
        />

        <div class="row">
            <div class="col">
                <div>
                    <input type="checkbox" name="change_returned" id="change_returned" checked/>
                    <label for="change_returned">Change Returned</label>
                </div>
            </div>
        </div>


        <x-form-group
            name="due_amount"
            placeholder="Due Amount"
            :value="$order->purchase_price ?? ''"
            column="col-md-12"
            readonly
        />

    </div>


    <script>

        $('#settle_advance').on('change', function(e) {
            if(e.target.checked == true){
                if($('#customer_id').val() > 0){
                    var grand_total = $('#payable_amount_hidden').val();
                    var give_total = $('#give_amount_hidden').val();
                    if(grand_total == 0){
                        console.log($('#settle_advance').checked)
                        $('#settle_advance').prop('checked', false);
                        var error = { status: 'crash', statusText: 'Select Items To Purchase First' };
                        return handleError(error);
                    }

                    var advanceRoute = "{{ route('pos.order.customer-wallet', ':customerId') }}";
                    advanceRoute = advanceRoute.replace(':customerId', $('#customer_id').val());
                    $.ajax({
                        type: 'GET',
                        url: advanceRoute,
                        dataType: 'JSON',
                        success: function (response) {
                            if(response.customer_wallet > parseInt(grand_total - give_total)){
                                $('#give_amount').val((grand_total-give_total));
                                $('#give_amount').prop('readonly', true);
                                $('#due_amount').val(0);
                                $('#change_amount').val(0);
                            }
                            else if(response.customer_wallet < parseInt(grand_total - give_total)){
                                $('#settle_advance').prop('checked', false);
                                var error = { status: 'crash', statusText: 'Insufficient Amount In Wallet' };
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
                $('#give_amount').prop('readonly', false);
            }
        })

        $('#give_amount').on('keyup', function(e) {
            console.log('hello');

            $('#due_amount').val(
                $('#give_amount').val() - $('#payable_amount_hidden').val() >= 0 ? 0 : (($('#payable_amount_hidden').val() - $('#give_amount_hidden').val()) - $('#give_amount').val())
            );

            $('#change_amount').val(
                $('#give_amount').val() - $('#payable_amount_hidden').val() > 0 ? ($('#give_amount').val() - ($('#payable_amount_hidden').val() - $('#give_amount_hidden').val())) : 0
            );
        });
    </script>

</x-form-modal>
