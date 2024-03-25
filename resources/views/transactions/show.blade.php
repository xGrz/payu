@php use Illuminate\Support\Number;use xGrz\PayU\Enums\PaymentStatus; @endphp
@extends('payu::app')

@section('content')

    @include('payu::transactions.partials.transaction_details')

{{--    <div class="pt-4 text-right">--}}
{{--        <x-payu::buttonlink href="{{route('payu.refunds.create', $transaction->id)}}">Make a refund</x-payu::buttonlink>--}}
{{--    </div>--}}
    @include('payu::transactions.partials.transaction_refunds')

@endsection

