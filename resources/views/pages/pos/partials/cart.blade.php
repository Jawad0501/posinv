@php
    $subtotal       = 0;
    $service_charge = setting('service_charge');
    $discount       = session()->has('pos_discount') ? session()->get('pos_discount')['amount'] : 0;
@endphp

@forelse (session()->get('pos_cart') as $key => $item)
    <tr>
        <td>{{ $item['name'] }}</td>
        <td>{{ convert_amount($item['price']) }}</td>
        <td>
            <div class="d-inline-flex">
                <button class="qty-btn" id="update-qty" data-key="{{ $key }}" data-role="minus">
                    <i class="fa-solid fa-minus"></i>
                </button>
                <input type="text" value="{{ $item['quantity'] }}" class="qty-input" min="1" readonly>
                <button class="qty-btn " id="update-qty" data-key="{{ $key }}" data-role="plus">
                    <i class="fa-solid fa-plus"></i>
                </button>
            </div>
        </td>
        @php
            $total    = $item['price'] * $item['quantity'];
            $subtotal += $total;
        @endphp
        <td>{{ convert_amount($total) }}</td>
        <td class="text-center">
            <a class="text-primary-500" href="{{ route('pos.menu.details', $key) }}" id="editBtn">
                <i class="fa-solid fa-edit"></i>
            </a>
            <a class="text-warning-500" href="{{ route('pos.cart.destroy', $key) }}" id="removeCartItem">
                <i class="fa-solid fa-trash-can"></i>
            </a>
        </td>
    </tr>

@empty
    <tr>
        <td colspan="7" class="text-danger text-center">Cart empty</td>
    </tr>
@endforelse

<input type="hidden" id="cart_subtotal" value="{{ $subtotal }}" />
<input type="hidden" id="cart_service_charge" value="{{ $service_charge }}" />
<input type="hidden" id="cart_discount" value="{{ $discount }}" />
<input type="hidden" id="cart_grandtotal" value="{{ ($subtotal + $service_charge) - $discount }}" />
\