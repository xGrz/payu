@php use \xGrz\PayU\Facades\Config; @endphp
@extends('p::app')


@section('content')
    @if(Config::getShopId())
        @include('payu::methods.content')
    @else
        @include('payu::infos.shop-id-missing')
    @endif


@endsection
