@php use \xGrz\PayU\Facades\Config; @endphp
@extends('p::app')

@section('css')
@endsection

@section('content')
    <div class="max-w-[800px] m-auto">
        <form action="{{route(Config::getRouteName('.payments.store'))}}" method="POST" class="grid gap-2">
            @csrf
            <x-p-paper>
                <x-slot:title>Customer</x-slot:title>
                @include('payu::transactions.create.customer')
            </x-p-paper>

            <x-p-paper>
                <x-slot:title>Order items</x-slot:title>
                @include('payu::transactions.create.items')
            </x-p-paper>

            @if($methods->count())
            <x-p-paper>
                <x-slot:title>Payment method</x-slot:title>
                @include('payu::transactions.create.paymethod')
            </x-p-paper>
            @endif

            <div class="text-center">
                <x-p-button type="submit" size="large">
                    Create order
                </x-p-button>
            </div>
        </form>
    </div>
@endsection
