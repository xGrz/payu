@php use Illuminate\Support\Number; use xGrz\PayU\Facades\Config @endphp
@extends('p::app')

@section('breadcrumbs')
    @if(!empty($shop))
        <div class="flex justify-end">
            <div class="bg-green-700 px-3 py-1 shrink rounded-l-md shadow-md text-white text-center">
                <small>ShopBalance balance:</small><br/>
                {{ $shop->humanAmount->balance }}
            </div>
            <a
                class="bg-green-700 hover:bg-green-800 cursor-pointer px-3 py-1 shrink rounded-r-md shadow-md text-white align-self-center"
                href="{{ route(Config::getRouteName('payouts.index')) }}"
            >
                <small>Payout</small>
            </a>
        </div>
    @endif
@endsection

@section('content')
    @livewire('payu-transactions-table')
@endsection

