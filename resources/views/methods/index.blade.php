@php use \xGrz\PayU\Facades\Config; @endphp
@extends('payu::app')


@section('content')
    @if(Config::getShopId())
        @include('payu::methods.content')
    @else
        @include('payu::infos.shop-id-missing')
    @endif


@endsection
