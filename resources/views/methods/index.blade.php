@extends('payu::app')


@section('content')
    @if(\xGrz\PayU\Facades\Config::getShopId())
        @include('payu::methods.content')
    @else
        @include('payu::infos.shop-id-missing')
    @endif


@endsection
