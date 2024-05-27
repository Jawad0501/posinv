@extends('layouts.app')
@section('title', 'POS')

@push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('build/assets/backend/plugins/slick/slick/slick.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('build/assets/backend/plugins/slick/slick/slick-theme.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('/') }}build/assets/backend/plugins/jquery-horizontal-scrollbars-master/jquery.horizontal.scroll.css"/>
@endpush

@section('content')

    <div class="col-xl-8 col-xxl-9 product-container">
        <div class="product-wrapper">

            <section class="section category-section mx-4" id="category-carousel"></section>

            <!-- Category Section End Here -->

            <!-- Product Section Start Here -->
            <section class="section product-section" id="product-section">
                <div class="row gy-3" id="allFoods"></div>
                <div class="pos-menu-loader w-100 h-100 d-none" style="background: #e7e7e7"></div>
            </section>
            <!-- Product Section End Here... -->

            {{-- <!-- Order Section Start Here... -->
            <x-pos.order-section class="d-none d-xl-block" />
            <!-- Order Section End Here --> --}}

        </div>
    </div>

    <div class="col-xl-4 col-xxl-3 pe-xl-0">
        <div class="pos-cart-wrapper">
            <div class="card cart-card">

                <div class="card-heading">
                    <!-- Tab Button Start -->
                    <!-- <ul class="nav nav-pills d-flex justify-content-evenly align-items-center">
                        <x-pos.cart-nav-button text="Dine In" href="{{ route('pos.table.create') }}" class="active" id="addBtn">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12.8226 23.9308H11.1084C10.7655 23.9308 10.4912 23.6542 10.4912 23.3085V23.0319C10.4912 22.6862 10.7655 22.4096 11.1084 22.4096H12.8226C13.1655 22.4096 13.4398 22.6862 13.4398 23.0319V23.3085C13.4398 23.6542 13.1655 23.9308 12.8226 23.9308Z" fill="white"/>
                                <path d="M12.7542 8.85788H11.3142V13.8361H12.7542V8.85788Z" fill="white"/>
                                <path d="M11.3142 23.2393C11.3142 23.6542 11.6571 23.9308 11.9999 23.9308C12.4114 23.9308 12.6856 23.5851 12.6856 23.2393V16.3943C12.6856 16.256 12.5485 16.1178 12.4114 16.1178H11.5885C11.4514 16.1178 11.3142 16.256 11.3142 16.3943V23.2393Z" fill="white"/>
                                <path d="M16.2513 13.836H12.7542H11.3142H7.74849C7.40563 13.836 7.13135 14.1126 7.13135 14.4583V15.1497C7.13135 15.4954 7.40563 15.772 7.74849 15.772H11.3142H12.7542H16.2513C16.5942 15.772 16.8685 15.4954 16.8685 15.1497V14.4583C16.7999 14.0435 16.5256 13.836 16.2513 13.836Z" fill="white"/>
                                <path
                                    d="M3.08571 24H2.46857C2.19429 24 2.05714 23.7925 2.05714 23.516L2.26286 21.4417C2.53714 18.4686 2.26286 15.5647 1.37143 12.7299C1.23429 12.3842 1.02857 12.1076 0.754286 11.9693C0.685714 11.9002 0.617143 11.9002 0.48 11.9002C0.205714 11.831 0 11.5545 0 11.2087C0 10.7248 0.48 10.379 0.96 10.5173C1.78286 10.7939 2.46857 11.4853 2.74286 12.3842C3.70286 15.3572 3.97714 18.4686 3.70286 21.6491L3.49714 23.6543C3.42857 23.8617 3.29143 24 3.08571 24Z"
                                    fill="white"/>
                                <path
                                    d="M7.33721 23.5851L6.92578 18.4687L8.36578 18.3304L8.77721 23.4469C8.77721 23.7234 8.5715 23.9308 8.36578 23.9308H7.74864C7.54292 24 7.33721 23.8617 7.33721 23.5851Z"
                                    fill="white"/>
                                <path
                                    d="M8.36562 19.5749H2.3999V17.7081H8.36562C8.91419 17.7081 9.32562 18.1229 9.32562 18.6761C9.32562 19.16 8.84562 19.5749 8.36562 19.5749Z"
                                    fill="white"/>
                                <path
                                    d="M20.9145 24H21.5316C21.8059 24 21.9431 23.7925 21.9431 23.516L21.7373 21.4417C21.4631 18.4686 21.7373 15.5647 22.6288 12.7299C22.7659 12.3842 22.9716 12.1076 23.2459 11.9693C23.3145 11.9002 23.3831 11.9002 23.5202 11.9002C23.7945 11.831 24.0002 11.5545 24.0002 11.2087C24.0002 10.7248 23.5202 10.379 23.0402 10.5173C22.2173 10.7939 21.5316 11.4853 21.2573 12.3842C20.2973 15.3572 20.0231 18.4686 20.2973 21.6491L20.5031 23.6543C20.5031 23.8617 20.7088 24 20.9145 24Z"
                                    fill="white"/>
                                <path
                                    d="M16.5943 23.5851L17.0057 18.4687L15.5657 18.3304L15.1543 23.516C15.1543 23.7926 15.36 24 15.5657 24H16.1829C16.3886 24 16.5943 23.8617 16.5943 23.5851Z"
                                    fill="white"/>
                                <path
                                    d="M15.6343 19.5749H21.6V17.7081H15.6343C15.0857 17.7081 14.6743 18.1229 14.6743 18.6761C14.6743 19.16 15.0857 19.5749 15.6343 19.5749Z"
                                    fill="white"/>
                                <path
                                    d="M11.6573 0.00783109C10.903 0.00783109 10.1487 0.146114 9.46303 0.35354C8.91446 0.560965 8.64017 1.18324 8.91446 1.73637C9.12017 2.08208 9.60017 2.28951 10.0116 2.15122C10.6287 1.9438 11.2459 1.87466 11.9316 1.87466C15.223 1.87466 17.9659 4.36376 18.4459 7.54427H5.48589C5.76017 5.74659 6.72017 4.22547 8.02303 3.18835C8.36589 2.91178 8.50303 2.42779 8.29732 2.01294C8.02303 1.45981 7.33731 1.32152 6.92589 1.66723C4.86874 3.25749 3.49731 5.81573 3.49731 8.65054V8.99625C3.49731 9.27282 3.70303 9.48024 3.97731 9.48024H19.8859C20.1602 9.48024 20.3659 9.27282 20.3659 8.99625V8.51226C20.4345 3.74148 16.4573 -0.199594 11.6573 0.00783109Z"
                                    fill="white"/>
                            </svg>
                        </x-pos.cart-nav-button>

                        <x-pos.cart-nav-button text="Takeway">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M16.1153 17.5778H1.9248V19.6902H16.1153V17.5778Z" fill="#ED7402"/>
                                <path
                                    d="M21.2948 19.6148H19.2372C18.6696 19.6148 18.2439 19.1621 18.2439 18.5586C18.2439 17.955 18.6696 17.5024 19.2372 17.5024H21.2948C21.8625 17.5024 22.2882 17.955 22.2882 18.5586C22.2882 19.1621 21.8625 19.6148 21.2948 19.6148Z"
                                    fill="#ED7402"/>
                                <path
                                    d="M11.6452 11.3916H7.74285C7.17523 11.3916 6.74951 10.939 6.74951 10.3354C6.74951 9.7319 7.17523 9.27925 7.74285 9.27925H11.6452C12.2129 9.27925 12.6386 9.7319 12.6386 10.3354C12.6386 10.939 12.2129 11.3916 11.6452 11.3916Z"
                                    fill="#ED7402"/>
                                <path
                                    d="M12.9226 7.46868H9.0202C8.45258 7.46868 8.02686 7.01604 8.02686 6.4125C8.02686 5.80897 8.45258 5.35632 9.0202 5.35632H12.9226C13.4902 5.35632 13.9159 5.80897 13.9159 6.4125C13.845 7.01604 13.4192 7.46868 12.9226 7.46868Z"
                                    fill="#ED7402"/>
                                <path
                                    d="M15.122 23.9904C14.6254 23.9149 14.2706 23.3114 14.3416 22.7833L18.2439 0.829834L20.1597 1.20704L16.2573 23.1605C16.1154 23.6886 15.6187 24.0658 15.122 23.9904Z"
                                    fill="#ED7402"/>
                                <path
                                    d="M22.0752 23.1605L18.3857 2.03692H5.61429L1.92476 23.1605C1.85381 23.6886 1.28619 24.0658 0.789521 23.9904C0.292854 23.9149 -0.061912 23.3114 0.00904045 22.7833L3.91142 0.678973C3.98238 0.226324 4.33714 0 4.6919 0H19.3081C19.7338 0 20.0176 0.301766 20.0886 0.678973L23.991 22.7833C24.0619 23.3114 23.7072 23.9149 23.2105 23.9904C22.7138 24.0658 22.1462 23.6886 22.0752 23.1605Z"
                                    fill="#ED7402"/>
                            </svg>
                        </x-pos.cart-nav-button>

                        <x-pos.cart-nav-button text="Delivery">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M18.3888 22.8531H5.64134C3.63223 22.8531 2.03882 21.2597 2.03882 19.2506V14.2625C2.03882 13.5697 2.59304 13.0847 3.21656 13.0847C3.90935 13.0847 4.39431 13.639 4.39431 14.2625V19.3199C4.39431 20.0127 4.94856 20.4976 5.57207 20.4976H18.3195C18.943 20.4976 19.4972 19.9434 19.4972 19.3199V11.3528C19.4972 10.66 20.0515 10.175 20.675 10.175C21.2985 10.175 21.8527 10.7292 21.8527 11.3528V19.3199C21.9913 21.2597 20.3979 22.8531 18.3888 22.8531Z"
                                    fill="#ED7402"/>
                                <path
                                    d="M20.5365 12.5305C19.4973 12.5305 18.4581 12.1841 17.5575 11.4913C16.8647 12.1148 15.8948 12.5305 14.8556 12.5305C13.8164 12.5305 12.7772 12.1841 12.0151 11.4913C11.253 12.1148 10.2138 12.5305 9.17465 12.5305C8.13546 12.5305 7.16555 12.1148 6.47275 11.4913C5.6414 12.1148 4.60222 12.5305 3.49375 12.5305C2.316 12.5305 1.20752 12.0455 0.584003 11.1449C-0.0395114 10.3135 -0.17807 9.20506 0.237606 8.09659L1.69248 4.0091C2.10815 2.83135 3.21663 2 4.53294 2H19.4973C20.7443 2 21.9221 2.83135 22.3377 4.0091L23.7926 8.09659C24.139 9.20506 24.0697 10.3135 23.4462 11.1449C22.7534 12.0455 21.7142 12.5305 20.5365 12.5305ZM17.4189 8.44299C17.8346 8.44299 18.181 8.65082 18.4581 8.99722C18.943 9.69001 19.7744 10.175 20.6057 10.175C21.0214 10.175 21.3678 10.0364 21.5757 9.82857C21.7142 9.62074 21.7835 9.27434 21.6449 8.92794L20.1901 4.84046C20.1208 4.63262 19.913 4.42478 19.6358 4.42478H4.67148C4.39437 4.42478 4.18654 4.56334 4.11726 4.84046L2.66239 8.92794C2.52383 9.27434 2.59311 9.62074 2.73167 9.82857C2.87023 10.0364 3.21662 10.175 3.70158 10.175C4.46365 10.175 5.36429 9.69001 5.84924 8.99722C6.12636 8.58154 6.54202 8.37371 7.02698 8.44299C7.51194 8.51227 7.85835 8.85866 7.99691 9.27434C8.13547 9.82857 8.6897 10.175 9.3825 10.175C10.1446 10.175 10.8374 9.75929 11.1145 9.13578C11.3223 8.7201 11.738 8.44299 12.2229 8.44299C12.7079 8.44299 13.1236 8.7201 13.3314 9.13578C13.6085 9.75929 14.3013 10.175 15.0634 10.175C15.7562 10.175 16.3104 9.82857 16.449 9.27434C16.5875 8.85866 16.9339 8.51227 17.4189 8.44299C17.2803 8.44299 17.3496 8.44299 17.4189 8.44299Z"
                                    fill="#ED7402"/>
                                <path
                                    d="M14.6477 22.8531H9.521C8.8282 22.8531 8.34326 22.2989 8.34326 21.6754V17.0336C8.34326 15.0245 9.93668 13.4311 11.9458 13.4311H12.2229C14.232 13.4311 15.8254 15.0245 15.8254 17.0336V21.6754C15.8254 22.3682 15.2712 22.8531 14.6477 22.8531ZM10.6988 20.4976H13.4007V17.0336C13.4007 16.3408 12.8464 15.7866 12.1536 15.7866H11.8765C11.1837 15.7866 10.6295 16.3408 10.6295 17.0336V20.4976H10.6988Z"
                                    fill="#ED7402"/>
                            </svg>
                        </x-pos.cart-nav-button>

                        @if ((bool) setting('reservation_allows'))
                            <x-pos.cart-nav-button text="Reservation" href="{{ route('orders.reservations.create') }}" id="addBtn">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M13.0353 18.3477H10.9177C10.6824 18.3477 10.4941 18.1595 10.4941 17.9242V17.2653C10.4941 17.03 10.6824 16.8418 10.9177 16.8418H13.0353C13.2706 16.8418 13.4588 17.03 13.4588 17.2653V17.9242C13.5059 18.1595 13.3177 18.3477 13.0353 18.3477Z" fill="#ED7402"/>
                                    <path d="M11.2942 17.6419C11.2942 18.0183 11.6236 18.3477 12.0001 18.3477C12.3765 18.3477 12.706 18.0183 12.706 17.6419V10.8183C12.706 10.6772 12.5648 10.536 12.4236 10.536H11.6236C11.4824 10.536 11.3412 10.6772 11.3412 10.8183V17.6419H11.2942Z" fill="#ED7402"/>
                                    <path d="M16.2354 8.27711H12.706H11.2942H7.71772C7.38831 8.27711 7.10596 8.55947 7.10596 8.88888V9.5477C7.10596 9.87711 7.38831 10.1595 7.71772 10.1595H11.2942H12.706H16.2354C16.5648 10.1595 16.8471 9.87711 16.8471 9.5477V8.88888C16.8471 8.55947 16.5648 8.27711 16.2354 8.27711Z" fill="#ED7402"/>
                                    <path d="M3.05882 18.4418H2.49412C2.25882 18.4418 2.07059 18.2536 2.07059 17.9712L2.25882 15.9006C2.54118 12.983 2.25882 10.0653 1.36471 7.28888C1.27059 6.91241 1.03529 6.67711 0.752941 6.48888C0.705882 6.44182 0.611765 6.39476 0.517647 6.39476C0.188235 6.30064 0 6.01829 0 5.73593C0 5.26535 0.470588 4.88888 0.941176 5.03005C1.78824 5.31241 2.44706 5.97123 2.72941 6.86535C3.67059 9.83005 4 12.9359 3.67059 16.0418L3.48235 18.0653C3.43529 18.2536 3.29412 18.4418 3.05882 18.4418Z" fill="#ED7402"/>
                                    <path
                                        d="M7.34107 18.0183L6.9646 12.983L8.37636 12.8889L8.79989 17.9713C8.79989 18.2065 8.61166 18.4418 8.37636 18.4418H7.7646C7.57636 18.3948 7.38813 18.2536 7.34107 18.0183Z"
                                        fill="#ED7402"/>
                                    <path
                                        d="M8.37662 14.0183H2.40015V12.1359H8.37662C8.89426 12.1359 9.31779 12.5594 9.31779 13.0771C9.31779 13.5947 8.89426 14.0183 8.37662 14.0183Z"
                                        fill="#ED7402"/>
                                    <path
                                        d="M20.9409 18.4418H21.5526C21.7879 18.4418 21.9762 18.2536 21.9762 17.9712L21.7879 15.9006C21.5056 12.983 21.7879 10.0653 22.682 7.28888C22.7762 6.95946 23.0115 6.67711 23.2938 6.48888C23.2938 6.44182 23.3879 6.39476 23.4821 6.39476C23.8115 6.30064 23.9997 6.01829 23.9997 5.73593C23.9997 5.26535 23.5291 4.88888 23.0585 5.03005C22.2115 5.31241 21.5526 5.97123 21.2703 6.86535C20.3291 9.83005 19.9997 12.9359 20.3291 16.0418L20.5173 18.0653C20.5644 18.2536 20.7056 18.4418 20.9409 18.4418Z"
                                        fill="#ED7402"/>
                                    <path
                                        d="M16.6588 18.0183L17.0352 12.983L15.6235 12.8889L15.2 17.9713C15.2 18.2065 15.3882 18.4418 15.6235 18.4418H16.2352C16.4235 18.3948 16.6117 18.2536 16.6588 18.0183Z"
                                        fill="#ED7402"/>
                                    <path
                                        d="M15.6233 14.0183H21.6468V12.1359H15.6233C15.1057 12.1359 14.6821 12.5594 14.6821 13.0771C14.6821 13.5947 15.1057 14.0183 15.6233 14.0183Z"
                                        fill="#ED7402"/>
                                </svg>
                            </x-pos.cart-nav-button>
                        @endif
                    </ul> -->
                    <!-- Tab Button End -->

                    <input type="hidden" name="order_type" id="order_type" value="Dine In" />
                    {{-- <input type="hidden" name="process_without_table"value="0" /> --}}

                    <input type="hidden" id="cart_update" value='@json(session()->get("cart_update"))' />
                    <input type="hidden" id="table_id" name="table_id" value="" />
                </div>

                <div class="card-body">
                    <div class="customer-selection">
                        <div class="row">
                            <div class="col-8 col-md-6 col-xl-8">
                                <div class="customer-area">
                                    <select name="customer" id="customer" class="form-control"></select>
                                </div>
                            </div>
                            <div class="col-4 col-md-6 col-xl-4 text-end">
                                <div class="add-edit-btn">
                                    <a href="#" class="btn" id="customerShow">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    <a href="#" class="btn" id="customerEdit">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <a href="{{ route('pos.customer.create') }}" class="btn" id="addBtn">
                                        <i class="fa-solid fa-plus"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Add TO Cart Start -->
                    <div class="row mt-3">
                        <div class="orders-title text-center fw-bold fs-4">
                            Sales History Details 
                        </div>
                        <div class="col-12">
                            <div class="table-view">
                                <table class="table font-secondary">
                                    <thead>
                                        <tr class="fs-16">
                                            <th>Name</th>
                                            <th>Price</th>
                                            <th>Qty</th>
                                            <th>Total</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="fs-12 text-gray-800 fw-bold" id="cartItems">

                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="card cart-subtotal border-0 rounded-20">
                                <div class="card-body font-secondary fs-16">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <p class="mb-0">
                                            <span class="fw-bolder">Sub Total:</span>
                                            <span class="fw-medium" id="cart_subtotal_amount">{{ convert_amount(0) }}</span>
                                        </p>
                                        <p class="mb-0">
                                            <span class="fw-bolder">Service:</span>
                                            <span class="fw-medium" id="cart_service_charge_amount">{{ convert_amount(0) }}</span>
                                        </p>
                                        <p class="mb-0">
                                            <span class="fw-bolder">Discount:</span>
                                            <span class="fw-medium" id="cart_discount_amount">{{ convert_amount(0) }}</span>
                                        </p>
                                    </div>
                                    <div class="text-center mt-1">
                                        <p class="mb-0 text-primary-800 fw-bolder fs-20">
                                            <span>Total:</span>
                                            <span id="cart_total_amount">{{ convert_amount(0) }}</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <!-- Add TO Cart End -->

                    <div class="row mt-3">

                        <x-pos.cart-place-button
                            text="Order"
                            icon="fa-check"
                            href="{{ route('pos.order.store') }}"
                            id="orderData"
                        />
                        {{-- <x-pos.cart-place-button
                            text="Pay"
                            icon="fa-credit-card"
                            class="pay-btn disabled"
                            id="showBtn"
                        />
                        <x-pos.cart-place-button
                            text="Print"
                            icon="fa-print"
                            href="{{ route('pos.cart.discount') }}"
                            id="printBtn"
                        /> --}}
                        <x-pos.cart-place-button
                            text="Discount"
                            icon="fa-percent"
                            href="{{ route('pos.cart.discount') }}"
                        />
                        {{-- <x-pos.cart-place-button
                            text="Add Misc"
                            icon="fa-plus"
                            href="{{ route('food.menu.create') }}"
                        /> --}}
                        <x-pos.cart-place-button
                            text="Delete"
                            icon="fa-trash-can"
                            href="{{ route('pos.cart.destroy') }}"
                            id="deleteBtn"
                        />

                    </div>
                </div>
            </div>
        </div>


        <div class="modal fade" id="paymentModal">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Finalize Order</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <form action="{{route('pos.payment.store')}}"  id="paymentForm" >
                        @csrf
                        <div class="modal-body">
                            <input type="hidden" name="order_id" id="order_id">
                            <input type="hidden" name="customer_id" id="customer_id">
                            <input type="hidden" name="payable_amount_hidden" id="payable_amount_hidden">


                            <div>
                                Payable Amount: <span id="payable_amount"></span>
                            </div>

                            <x-form-group name="discount_amount" placeholder="Enter discount amount"/>

                            <x-form-group name="discount_type" isType="select">
                                <option value="fixed">Fixed</option>
                                <option value="percentage">Percentage</option>
                            </x-form-group>

                            <x-form-group name="payment_method" isType="select">
                                    <option value="Cash">Cash</option>
                                    <option value="Card">Card</option>
                            </x-form-group>

                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="" type="checkbox" id="settle_advance" name="settle_advance">
                                    <label class="form-check-label" for="settle_advance">Settle From Wallet ( <span  id="customer_wallet"></span> )</label>
                                </div>
                            </div>

                            <x-form-group name="give_amount" id="give_amount" placeholder="Enter given amount"/>

                            <x-form-group name="change_amount" id="change_amount"  placeholder="Enter change amount" :value="0" :readonly="true" />

                            <div class="d-flex form-group align-items-center">
                                <input type="checkbox" name="change_returned" id="change_returned" class="me-2" checked>
                                <label for="change_returned" class="form-label">Changed Amount Returned</label>
                            </div>

                            <x-form-group name="due_amount" id="due_amount"  placeholder="Enter due amount" :value="0" :readonly="true" />
                            
                            <input type="hidden" name="finalize_order" id="finalize_order">

                        </div>


                        <div class="modal-footer">

                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <x-submit-button text="Submit" type="button" id="payment_submit"/>

                        </div>

                    </form>

                </div>
            </div>
        </div>

    </div>
@endsection

@push('js')
    <script type="text/javascript" src="{{ asset('build/assets/backend/plugins/slick/slick/slick.min.js') }}"></script>
    <script>

        $('#payment_submit').on('click', function(){
            console.log('hello');
            $.ajax({
                url: $('#paymentForm').attr('action'),
                method: 'POST',
                data: $('#paymentForm').serialize(),
                success: function(response) {
                    $('#paymentModal').modal('hide');
                    location.reload();
                },
                error: function(xhr) {
                    alert('An error occurred while processing the payment.');
                }

            })
        })

        
        $('#settle_advance').on('change', function(e) {
            if(e.target.checked == true){
                if($('#customer_id').val() > 0){
                    var grand_total = $('#payable_amount_hidden').val();
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
                            if(response.customer_wallet> parseInt(grand_total)){
                                $('#give_amount').val(grand_total);
                                $('#give_amount').prop('readonly', true);
                                $('#due_amount').val(0);
                            }
                            else if(response.customer_wallet< parseInt(grand_total)){
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
                    var error = { status: 'crash', statusText: 'Select Customer First' };
                    handleError(error);
                }
                
                
            }
            else{
                $('#paid').prop('readonly', false);
            }
        })
    </script>
@endpush


