@extends('mails.layout')

@section('content')
    <p><strong>Hi </strong>{{ $user->full_name }},</p>
    <div class="">
        <p class="message">You have request for verification your account in our system. Please enter your verification code & verify your account.</p>

        <div class="details">
            <p>OTP: {{ $otpCode }}</p>
        </div>
    </div>
@endsection
