@forelse ($orders as $order)
    <div class="order-items">
        <div class="order-item" id="order-item" data-id="{{ $order->id }}">
            <div>
                <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M21.4871 23.4371H2.56641V26.2536H21.4871V23.4371Z" fill="#ED7402" />
                    <path
                        d="M28.3931 26.153H25.6496C24.8928 26.153 24.3252 25.5495 24.3252 24.7448C24.3252 23.9401 24.8928 23.3365 25.6496 23.3365H28.3931C29.1499 23.3365 29.7176 23.9401 29.7176 24.7448C29.7176 25.5495 29.1499 26.153 28.3931 26.153Z"
                        fill="#ED7402" />
                    <path
                        d="M15.5269 15.1889H10.3237C9.5669 15.1889 8.99927 14.5853 8.99927 13.7806C8.99927 12.9759 9.5669 12.3724 10.3237 12.3724H15.5269C16.2837 12.3724 16.8513 12.9759 16.8513 13.7806C16.8513 14.5853 16.2837 15.1889 15.5269 15.1889Z"
                        fill="#ED7402" />
                    <path
                        d="M17.23 9.95827H12.0268C11.27 9.95827 10.7024 9.35473 10.7024 8.55002C10.7024 7.74532 11.27 7.14178 12.0268 7.14178H17.23C17.9868 7.14178 18.5545 7.74532 18.5545 8.55002C18.4599 9.35473 17.8922 9.95827 17.23 9.95827Z"
                        fill="#ED7402" />
                    <path d="M20.1628 31.9872C19.5006 31.8866 19.0276 31.0819 19.1222 30.3777L24.3253 1.10645L26.8796 1.60939L21.6764 30.8807C21.4872 31.5848 20.825 32.0877 20.1628 31.9872Z"
                        fill="#ED7402" />
                    <path d="M29.4337 30.8807L24.5143 2.71589H7.48572L2.56635 30.8807C2.47175 31.5848 1.71492 32.0878 1.0527 31.9872C0.390473 31.8866 -0.0825493 31.0819 0.0120539 30.3778L5.21523 0.905298C5.30983 0.301766 5.78286 0 6.25587 0H25.7441C26.3118 0 26.6902 0.402355 26.7848 0.905298L31.9879 30.3778C32.0825 31.0819 31.6095 31.8866 30.9473 31.9872C30.2851 32.0878 29.5283 31.5848 29.4337 30.8807Z" fill="#ED7402" />
                </svg>
            </div>
            <div class="description">
                <h6 class="name">{{ $order->invoice }}</h6>
                <p class="table-name">
                    Customer: {{ $order->user->first_name}}
                </p>
                <span class="order-status">Status: {{ $order->status }}</span>
            </div>
            <div class="dropdown ms-auto me-2">
                <div class="dropdown-toggle" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                    <svg xmlns="http://www.w3.org/2000/svg" height="20" width="5" viewBox="0 0 128 512"><path fill="#ff5c5c" d="M64 360a56 56 0 1 0 0 112 56 56 0 1 0 0-112zm0-160a56 56 0 1 0 0 112 56 56 0 1 0 0-112zM120 96A56 56 0 1 0 8 96a56 56 0 1 0 112 0z"/></svg>
                </div>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                    <!-- <li>
                        <a href="{{ route('pos.order.print-kot', $order->id) }}" class="" id="printBtn">
                            <i class="fa-solid fa-print"></i>
                            <span class="ms-1">Print KOT</span>
                        </a>
                    </li> -->
                    <li>
                        <a href="{{ route('pos.payment.create') }}?order_id={{ $order->id }}" class="" id="addBtn">
                            <i class="fa-solid fa-print"></i>
                            <span class="ms-1">Receive Payment</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('pos.order.show', $order->id) }}" class="" id="showBtn">
                            <i class="fa-solid fa-store"></i>
                            <span class="ms-1">Order Details</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('pos.order.edit', $order->id) }}" class="" id="editOrder">
                            <i class="fa-solid fa-edit"></i>
                            <span class="ms-1">Edit Order</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('pos.order.cancel', $order->id) }}" class="" id="cancelOrder">
                            <i class="fa-solid fa-xmark"></i>
                            <span class="ms-1">Cancel Order</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
@empty

@endforelse


