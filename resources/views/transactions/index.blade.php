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
{{--    <x-p-pagination info-only :source="$transactions"/>--}}
    <x-p-paper class="bg-slate-800">
        <x-slot:title>Transactions listing</x-slot:title>
        <x-slot:actions>
            <x-p-button href="{{route(Config::getRouteName('payments.create'))}}">Wizard</x-p-button>
            <form action="{{route(Config::getRouteName('.payments.storeFake'))}}" method="POST" id="createTransaction" class="hidden">
                @csrf
            </form>
            <x-p-button type="submit" form="createTransaction" color="success">
                Fake payment
            </x-p-button>
            <x-p-button href="https://merch-prod.snd.payu.com/user/login?lang=pl" target="new" color="warning">
                PayU-Panel
            </x-p-button>
        </x-slot:actions>


        @livewire('payu-transactions-table')

    </x-p-paper>
{{--    <x-p-pagination info-only= :source="$transactions"/>--}}
@endsection

