@extends('mails.layout')

@section('content')

    <div class="">
        <p><strong>Hi {{ auth()->user()->full_name }}</strong>,</p>
        <p class="message">
            Thank you for your order.
        </p>

        <div>
            We look forward to serving you, please don’t hesitate to contact us for any questions or concerns.
        </div>
    </div>
@endsection
