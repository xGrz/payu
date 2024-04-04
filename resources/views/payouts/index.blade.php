@php use Illuminate\Support\Number; @endphp
@extends('payu::app')

@section('breadcrumbs')
    @if($balance)
        {{ $balance->name }}
    @endif
@endsection

@section('content')
    @if(\xGrz\PayU\Facades\Config::getShopId())
        @include('payu::payouts.partials.payout-index')
    @else
        @include('payu::infos.shop-id-missing')
    @endif
@endsection
