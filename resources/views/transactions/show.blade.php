@php use Illuminate\Support\Number;use xGrz\PayU\Enums\PaymentStatus; @endphp
@extends('payu::app')

@section('content')

    @include('payu::transactions.partials.transaction_details')
    @include('payu::transactions.partials.transaction_refunds')

@endsection

