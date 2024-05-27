<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Order</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cabin:wght@500&family=Nunito:wght@400;700&family=Open+Sans:wght@300;400;600&display=swap" rel="stylesheet">

    <style>
        /* @import url('https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;700;800&display=swap'); */

        * {
            box-sizing: border-box;
            padding: 0;
            margin: 0;
        }

        body {
            font-family: 'Nunito', sans-serif;
            background-color: #fffcf9;
            font-weight: 400;
            line-height: 1rem;
        }

        a {
            text-decoration: none;
        }

        .main {
            width: 300px;
            background-color: #fff;
            margin: auto;
        }
        .main .logo {
            width: 100px;
        }
        @media (min-width: 576px) {
            .main {
                width: 384px;
            }
        }

        .main .confirmation {
            background-color: #FEF4E1;
            text-align: center;
            padding: 30px 0;
        }

        .main .confirmation .title {
            font-size: 26px;
            line-height: 39px;
            color: #ED7402;
            font-weight: 800;
            margin-top: 32px;
        }

        .main .confirmation .message {
            font-family: 'Open Sans', sans-serif;
            font-size: 16px;
            line-height: 24px;
            color: #262626;
        }

        .main .confirmation .image {
            margin: 32px 0;
        }

        .main .confirmation .table_cards {
            display: flex;
            align-items: center;
            justify-content: space-evenly;
        }

        .main .confirmation .table_cards .table_card {
            background: #fff;
            box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.05);
            border-radius: 10px;
        }

        .main .confirmation .table_cards .table_card .name {
            font-family: 'Open Sans', sans-serif;
            font-weight: 700;
            font-size: 16px;
            background-color: #ED7402;
            padding: 4px 16px;
            border-radius: 10px 10px 0px 0px;
            color: #fff;
        }

        .main .confirmation .table_cards .table_card h2 {
            font-weight: 800;
            color: #434343;
            font-size: 36px;
            margin: 20px 0;
        }

        .main .order_summeries {
            padding: 25px 0px;
        }

        .main .order_summeries .summery {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0px 16px;
        }

        .main .order_summeries .summery h4 {
            font-size: 22px;
            font-weight: 800;
            color: #434343;
            margin-bottom: 0;
        }

        .main .order_summeries .summery .info {
            text-align: right;
        }

        .main .order_summeries .summery .info .invoice_no {
            font-family: 'Open Sans', sans-serif;
            font-weight: 700;
            font-size: 14px;
            line-height: 24px;
            color: #121212;
            margin-bottom: 0;
        }

        .main .order_summeries .summery .info .date_time {
            font-family: 'Open Sans', sans-serif;
            font-size: 12px;
            line-height: 16px;
            color: #121212;
            margin-bottom: 0;
        }

        .main .order_summeries .orders {
            padding: 20px 16px;
            border-bottom: 1px solid #E9E9E9;
        }

        .main .order_summeries .orders .order {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .main .order_summeries .orders .order .food {
            font-family: 'Open Sans', sans-serif;
            color: #434343;
            width: 70%;
        }

        .main .order_summeries .orders .order .food .name {
            font-weight: 700;
            font-size: 14px;
            margin-bottom: 0;
        }

        .main .order_summeries .orders .order .food .addons {
            margin-bottom: 0;
            margin-left: 25px;
        }

        .main .order_summeries .orders .order .food .addons li {
            font-size: 12px;
        }

        .main .order_summeries .orders .order .qty {
            font-family: 'Open Sans', sans-serif;
            color: #434343;
            font-size: 12px;
            width: 15%;
        }

        .main .order_summeries .orders .order .price {
            font-family: 'Open Sans', sans-serif;
            font-weight: 700;
            color: #434343;
            font-size: 14px;
            width: 15%;
        }

        .main .order_summeries .finance {
            padding: 20px 16px;
            border-bottom: 1px solid #E9E9E9;
        }

        .main .order_summeries .finance .single-line {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 14px;
            font-family: 'Open Sans', sans-serif;
            font-weight: 700;
            color: #000;
            margin-bottom: 7px;
        }

        .main .order_summeries .finance .single-line.success {
            color: #00A600;
        }

        .main .order_summeries .finance-total {
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Open Sans', sans-serif;
            font-weight: 700;
            color: #000;
            margin: 40px 0;
            font-size: 30px;
        }

        .main .order_summeries .finance-total .amount {
            color: #ED7402;
            margin-left: 5px;
        }

        .main .order_summeries .order-more {
            text-align: center;
        }

        .main .order_summeries .order-more .order-btn {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 202px;
            height: 40px;
            background: #fff;
            border: 1px solid #D9D9D9;
            border-radius: 37px;
            margin: 0 auto;
            font-family: 'Open Sans', sans-serif;
            font-weight: 700;
            font-size: 14px;
            line-height: 24px;
            color: #262626;
        }

        .main .order_summeries .order-more .order-btn img {
            margin-left: 8px;
        }

        .main .order_summeries .order-more .order-btn:hover {
            color: #262626 !important;
        }

        .main .customer-info {
            padding: 15px 16px;
        }

        .main .customer-info h6 {
            font-weight: 800;
            font-size: 22px;
            line-height: 33px;
            color: #434343;
        }

        .main .customer-info .infos {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-family: 'Open Sans', sans-serif;
            color: #434343;
        }

        .main .customer-info .infos .name-area {
            width: 50%;
        }

        .main .customer-info .infos .address-area {
            width: 50%;
            text-align: right;
        }

        .main .customer-info .infos .mn {
            font-weight: 700;
            font-size: 14px;
            line-height: 24px;
            margin-bottom: 0;
        }

        .main .customer-info .infos .sub {
            font-size: 12px;
            margin-bottom: 0;
        }

        .main .question-area {
            margin: 15px 16px;
        }

        .main .question-area .query {
            background-color: #F5F5F5;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 8px 34px;
            border-radius: 13px;
        }

        .main .question-area .query img {
            width: 80px;
            height: 52.23px;
        }

        .main .question-area .query .details {
            font-family: 'Open Sans', sans-serif;
            margin-left: 10px;
        }

        .main .question-area .query .details .title {
            font-weight: 700;
            font-size: 14px;
            line-height: 24px;
            color: #262626;
            margin-bottom: 0;
        }

        .main .question-area .query .details .des {
            font-size: 12px;
            color: #434343;
            margin-bottom: 0;
        }

        .main .question-area .query .details .des a {
            color: #ED7402;
        }

        .main .response-area {
            display: flex;
            padding: 8px 0px;
            margin: 25px 16px;
            background: #FEF4E1;
            border: 1px dashed #ED7402;
            border-radius: 10px;
            text-align: center;
            font-family: 'Open Sans', sans-serif;
            font-weight: 700;
            font-size: 16px;
            color: #ED7402;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .main .footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin: 25px 16px;
            padding-bottom: 20px;
        }

        .main .footer .footer_single {
            font-family: 'Open Sans', sans-serif;
        }

        .main .footer .footer_single .info {
            font-size: 14px;
            line-height: 16px;
            color: #121212;
            margin-top: 16px;
        }

        .main .footer .footer_single .info p {
            margin-bottom: 5px;
        }

        .main .footer .footer_single .follow-us {
            font-weight: 700;
            font-size: 14px;
            line-height: 24px;
            color: #ED7402;
            text-align: right;
        }

        .main .footer .footer_single .social {
            text-align: center;
            margin-top: 7px;
        }

        .main .footer .footer_single .social a:not(:first-child) {
            margin-left: 7px;
        }
    </style>

</head>

<body>
    <main class="main">
        <div class="confirmation">
            <a href="#">
                <img src="{{ uploaded_file(setting('dark_logo')) }}" alt="logo" class="logo" />
            </a>
            <h5 class="title">Order Confirmed!</h5>
            <p class="message">
                Your order has been confirmed
                Your food will delivered
                to your address as soon as possible
            </p>

            <div class="image">
                <img src="{{ asset('assets/frontend/images/email.svg') }}" alt="email" />
            </div>

            <div class="table_cards">
                <div class="table_card">
                    <div class="name">Table NO</div>
                    <h2>{{ $order->table_id }}</h2>
                </div>
                <div class="table_card">
                    <div class="name">Invoice</div>
                    <h2>{{ $order->invoice }}</h2>
                </div>
            </div>
        </div>

        <div class="order_summeries">
            <div class="summery">
                <h4>Order Summery</h4>
                <div class="info">
                    <h5 class="invoice_no">{{ $order->invoice }}</h5>
                    <p class="date_time">{{ format_date($order->created_date, true) }}</p>
                </div>
            </div>

            <div class="orders">
                @foreach ($order->orderDetails as $orderDetails)
                    <div class="order">
                        <div class="food">
                            <p class="name">{{ $orderDetails->food->name }}</p>
                            @foreach ($orderDetails->addons as $orderAddonDetails)
                                <ul class="addons">
                                    <li>{{ $orderAddonDetails->addon->name }}</li>
                                </ul>
                            @endforeach
                        </div>
                        <div class="qty">x{{ $orderDetails->quantity }}</div>
                        <div class="price">{{ convert_amount($orderDetails->price) }}</div>
                    </div>
                @endforeach
            </div>

            <div class="finance">
                <div class="single-line">
                    <span>Sub Total</span>
                    <span>{{ convert_amount($order->orderDetails->sum('total_price')) }}</span>
                </div>
                <div class="single-line">
                    <span>Delivery Charge</span>
                    <span>{{ convert_amount($order->delivery_charge) }}</span>
                </div>
                <div class="single-line">
                    <span>Service Charge</span>
                    <span>{{ convert_amount($order->service_charge) }}</span>
                </div>
                <div class="single-line">
                    <span>Vat</span>
                    <span>{{ convert_amount($order->orderDetails->sum('vat')) }}%</span>
                </div>
                <div class="single-line">
                    <span>Discount</span>
                    <span>-{{ convert_amount($order->discount) }}</span>
                </div>
                @if ($order->rewards_amount > 0)
                <div class="single-line">
                    <span>Rewards Amount</span>
                    <span>-{{ convert_amount($order->rewards_amount) }}</span>
                </div>
                @endif

                <div class="single-line success">
                    <span>Reward : Green Reward</span>
                    <span>{{ $order->rewards }}</span>
                </div>
            </div>

            <div class="finance-total">
                <h6>Total:</h6>
                <h6 class="amount">{{ convert_amount($order->grand_total) }}</h6>
            </div>

            <div class="order-more">
                <a href="/" class="order-btn">
                    Order More
                    <img src="{{ asset('assets/frontend/images/icons/arrow-left.png') }}" alt="" srcset="">
                </a>
            </div>
        </div>

        <div class="customer-info">
            <h6>Customer Info</h6>

            <div class="infos">
                <div class="name-area">
                    <div>
                        <h6 class="mn">Customer Name</h6>
                        <p class="sub">{{ $order->user->full_name }}</p>
                    </div>
                    @if ($order->payment != null)
                        <div>
                            <h6 class="mn">Payment Method</h6>
                            <p class="sub">{{ $order->payment->method }}</p>
                        </div>
                    @endif
                </div>
                <div class="address-area">
                    <div>
                        <h6 class="mn">Delivery Address</h6>
                        <p class="sub">{{ $order->address->location }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="question-area">
            <div class="query">
                <img src="{{ asset('assets/frontend/images/mail.svg') }}" alt="mail" />
                <div class="details">
                    <h5 class="title">Any Question ? </h5>
                    <p class="des">
                        If you need any help email us :
                        <a href="mailto:{{ setting('email') }}">{{ setting('email') }}</a>
                    </p>
                </div>
            </div>
        </div>

        <div class="response-area">
            Thanks for ordering with Eatery.
        </div>

        <footer class="footer">
            <div class="footer_single" style="width: 75%;">
                <a href="#">
                    <img src="{{ uploaded_file(setting('dark_logo')) }}" alt="logo" class="logo" />
                </a>
                <div class="info">
                    <p>{{ setting('address') }}</p>
                    <p>Call : {{ setting('phone') }}</p>
                </div>
            </div>
            <div class="footer_single">
                <h5 class="follow-us">Follow Us ?</h5>
                <div class="social">
                    <a href="#">
                        <img src="{{ asset('assets/frontend/images/icons/facebook.png') }}" alt="facebook" />
                    </a>
                    <a href="#">
                        <img src="{{ asset('assets/frontend/images/icons/google.png') }}" alt="google" />
                    </a>
                </div>
            </div>
        </footer>

    </main>
</body>

</html>
