@extends('p::app')

@section('content')

    @livewire('payu-transaction-show', ['transaction' => $transaction])
    @livewire('payu-refunds-listing', [
        'tableTitle' => 'Refunds to transaction',
        'shouldRenderAction' => true,
        'transaction' => $transaction
    ])

{{--    @include('payu::transactions.partials.transaction_details')--}}
{{--    @include('payu::transactions.partials.transaction_refunds', [--}}
{{--        'tableTitle' => 'Refunds to transaction',--}}
{{--        'shouldRenderAction' => true--}}
{{--    ])--}}

@endsection

