@php use Illuminate\Support\Number;use xGrz\PayU\Enums\PaymentStatus; use xGrz\PayU\Facades\Config @endphp
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
    <x-p-pagination info-only :source="$transactions"/>
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


        @if($transactions->count())
            <x-p-table>
                <x-p-thead class="text-left text-white leading-8">
                    <x-p-tr>
                        <x-p-th>Description</x-p-th>
                        <x-p-th>Value</x-p-th>
                        <x-p-th class="text-right">Refunded</x-p-th>
                        <x-p-th class="text-center">Status</x-p-th>
                        <x-p-th class="text-right">Created</x-p-th>
                        <x-p-th class="text-right">Updated</x-p-th>
                        <x-p-th class="text-right">Actions</x-p-th>
                    </x-p-tr>
                </x-p-thead>
                <tbody class="leading-tight">
                @foreach($transactions as $transaction)
                    <x-p-tr class="hover:bg-gray-100">
                        <x-p-td>
                            @if($transaction->status === PaymentStatus::INITIALIZED)
                                <span class="text-gray-300">{{ $transaction->payload['description']  }}</span>
                            @else
                                <x-p-link href="{{ route(Config::getRouteName('payments.show'), $transaction->id )}}">
                                    {{ $transaction->payload['description']  }}
                                </x-p-link>
                            @endif
                        </x-p-td>
                        <x-p-td class="text-right">
                            {{ Number::currency($transaction->payload['totalAmount'] / 100, $transaction->payload['currencyCode'], 'pl') }}
                            @if($transaction->payMethod)
                                <small class="block">
                                    {{$transaction->payMethod->name}}
                                </small>
                            @endif
                        </x-p-td>
                        <x-p-td class="text-right">
                            @if($transaction->hasSuccessfulRefunds())
                                <small
                                    class="block text-green-500">{{ humanAmount($transaction->refundedAmount()) }}</small>
                            @endif
                            @if($transaction->hasDefinedRefunds())
                                <small
                                    class="block text-slate-500">{{ humanAmount($transaction->getDefinedRefundsTotalAmount()) }}</small>
                            @endif
                            @if($transaction->hasFailedRefunds())
                                <small
                                    class="block text-red-500">{{ humanAmount($transaction->getFailedRefundsTotalAmount()) }}</small>
                            @endif
                        </x-p-td>
                        <x-p-td>
                            <x-p-status :status="$transaction->status" class="text-sm mx-2"/>
                        </x-p-td>
                        <x-p-td class="text-right">{{ $transaction->created_at }}</x-p-td>
                        <x-p-td
                            class="text-right">{{ $transaction->created_at == $transaction->updated_at ? '' : $transaction->updated_at}}</x-p-td>
                        <x-p-td class="text-nowrap text-right">
                            @include('payu::transactions.partials.transaction_actions')
                        </x-p-td>
                    </x-p-tr>
                @endforeach
                </tbody>
            </x-p-table>

            <div class="py-3">
                <x-p-pagination :source="$transactions"/>
            </div>
        @else
            <x-p-not-found message="Transactions for found."/>
        @endif


    </x-p-paper>
    <x-p-pagination info-only= :source="$transactions"/>
@endsection

