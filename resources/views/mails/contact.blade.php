@extends('mails.layout')

@section('content')
    <p><strong>Subject: </strong>{{ $contact->subject }}</p>
    <div class="">
        <p class="message">{{ $contact->message }}</p>

        <div class="details">
            <p><strong>Customer Info:</strong></p>
            <p>Name: {{ $contact->name }}</p>
            <p>Phone Number: {{ $contact->phone }}</p>
            <p>Email: {{ $contact->email }}</p>
        </div>
    </div>
@endsection
