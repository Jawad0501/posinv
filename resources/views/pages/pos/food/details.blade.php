<x-form-modal
    :title="$ingredient ? 'Update Product Details' : 'Product Details'"
    action="{{ route('pos.cart.store') }}"
    :button="$ingredient ? 'Update Cart' : 'Add to Cart'"
    id="form"
>
    <input type="hidden" name="food_id" id="food_id" value="{{ $ingredient->id }}">

    <div>
        <div class="d-flex justify-content-between align-items-center">
            <label for="">Product Name: {{ $ingredient->name }}</label>
        </div>
        <div class=" mt-2">

            <div>
                @if ($ingredient_purchase_items->count() > 0)
                    <x-form-group
                        name="lot"
                        isType="select"
                        class="select2"
                        column="col-12"
                    >
                        <option value="">Select Lot</option>
                        @php
                            $count = 1;
                        @endphp
                        @foreach($ingredient_purchase_items as $item)
                            <option value="{{$item->id}}">Lot: {{$count}} (In Stock: {{$item->quantity_amount - $item->sold_qty}}, Unit Price: {{$item->unit_price}})</option>
                            {{$count = $count+1}}
                        @endforeach
                    </x-form-group>
                    <!-- <select name="purchase_id" id="purchase_id" class="select2">
                        @php
                            $count = 1;
                        @endphp
                        @foreach($ingredient_purchase_items as $item)
                            <option value="{{$item->id}}">Lot: {{$count}} (In Stock: {{$item->quantity_amount - $item->sold_qty}}, Unit Price: {{$item->unit_price}})</option>
                            {{$count+1}}
                        @endforeach
                    </select> -->
                @endif

                <div class="form-group mt-3">
                    <label for="unit_sell_price" class="form-label">Unit Sell Price</label>
                    <input type="text" id="unit_sell_price" name="unit_sell_price" class="form-control">

                </div>

            </div>

            <div class="d-inline-flex mt-3">
                <label for="quantity">Quantity &nbsp;</label>
                <span class="qty-btn" id="update-qty" data-role="minus">
                    <i class="fa-solid fa-minus"></i>
                </span>
                <input type="text" class="qty-input" name="quantity" id="quantity" value="1" />
                <span class="qty-btn" id="update-qty" data-role="plus">
                    <i class="fa-solid fa-plus"></i>
                </span>
            </div>
        </div>
    </div>

</x-form-modal>
