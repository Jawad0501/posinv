@extends('layouts.app')

@section('title', 'Forbidden')

@section('content')
    <x-exception-error code="403" :message="$exception->getMessage() ?: 'Forbidden'"/>
@endsection