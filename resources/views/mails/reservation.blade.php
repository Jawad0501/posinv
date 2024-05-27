@extends('mails.layout')

@section('content')

    <div class="">
        <p><strong>Hi {{ $reservation->name }}</strong>,</p>
        <p class="message">
            @if ($is_update)
                Your reservation has been {{ $reservation->status }}. For any queries please call {{ setting('phone') }}.
            @else
                Thank you for booking your table with us! Your reservation for {{ $reservation->phone }} people on
                {{ format_date($reservation->start_date) }} {{ $reservation->start_time }} is confirmed. For any changes please call {{ setting('phone') }}.
            @endif
        </p>

        <div class="details">
            <p><strong>Your Reservation:</strong></p>
            <p>Name: {{ $reservation->name }}</p>
            <p>Phone Number: {{ $reservation->phone }}</p>
            <p>Email: {{ $reservation->email }}</p>
            <p>Party Size: {{ $reservation->total_person }}</p>
            <p>Booking Date: {{ date('D, M Y', strtotime($reservation->expected_date)) }}</p>
            <p>Time: {{ date('h:i A', strtotime($reservation->expected_time)) }}</p>
        </div>

        <div>
            We look forward to serving you, please don’t hesitate to contact us for any questions or concerns.
        </div>
    </div>
@endsection
