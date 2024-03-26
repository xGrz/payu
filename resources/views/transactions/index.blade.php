@php use Illuminate\Support\Number;use xGrz\PayU\Enums\PaymentStatus; @endphp
@extends('payu::app')

@section('breadcrumbs')
    @if(!empty($shop))
        <div class="flex justify-end">
            <div class="bg-green-700 px-3 py-1 shrink rounded-l-md shadow-md text-white text-center">
                <small>ShopBalance balance:</small><br/>
                {{ $shop->humanAmount->balance }}
            </div>
            <a
                class="bg-green-700 hover:bg-green-800 cursor-pointer px-3 py-1 shrink rounded-r-md shadow-md text-white align-self-center"
                href="{{ route('payu.payouts.index') }}"
            >
                <small>Payout</small>
            </a>
        </div>
    @endif
@endsection

@section('content')
    @if($balance)
        @include('payu::balance.balance')
    @endif
    <div class="mb-2" xmlns:x-payu="http://www.w3.org/1999/html">
        <form action="{{route('payu.payments.store')}}" method="POST" id="createTransaction">
            @csrf
        </form>
        <x-payu::button type="submit" form="createTransaction" color="success">
            Create fake payment
        </x-payu::button>
        <x-payu::buttonlink href="https://merch-prod.snd.payu.com/user/login?lang=pl" target="new" color="warning">
            PayU-Panel
        </x-payu::buttonlink>
    </div>

    <x-payu::paper>
        <table class="w-full">
            <thead>
            <tr>
                <th>Description</th>
                <th>Value</th>
                <th>Status</th>
                <th>Link</th>
                <th class="text-right">Created</th>
                <th class="text-right">Updated</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($transactions as $transaction)
                <tr class="hover:bg-gray-100">
                    <td>
                        @if($transaction->status === PaymentStatus::INITIALIZED)
                            <span class="text-gray-300">{{ $transaction->payload['description']  }}</span>
                        @else
                            <x-payu::link
                                href="{{ route('payu.payments.show', $transaction->id )}}"
                            >
                                {{ $transaction->payload['description']  }}
                            </x-payu::link>
                        @endif
                    </td>
                    <td class="text-right">
                        {{ Number::currency($transaction->payload['totalAmount'] / 100, $transaction->payload['currencyCode'], 'pl') }}
                    </td>
                    <td>
                        <x-payu::status :status="$transaction->status" class="text-sm mx-2"/>
                    </td>
                    <td class="text-center">
                        <x-payu::link href="{!! $transaction->link !!}" target="new">
                            @if(!$transaction->payMethod?->name)
                                Payment link
                            @else
                                <img
                                    src="{{ $transaction->payMethod->image }}"
                                    alt="{{ $transaction->payMethod->name }}"
                                    style="max-height: 30px; max-width: 60px;"
                                />
                            @endif
                        </x-payu::link>
                    </td>
                    <td class="text-right">{{ $transaction->created_at }}</td>
                    <td class="text-right">{{ $transaction->created_at == $transaction->updated_at ? '' : $transaction->updated_at}}</td>
                    <td>
                        @if($transaction->status->actionAvailable('delete'))
                            <form action="{{route('payu.payments.destroy', $transaction->id)}}" method="POST"
                                  id="delete_{{$transaction->id}}">
                                @csrf @method('DELETE')
                            </form>
                            <button type="submit" form="delete_{{$transaction->id}}" class="text-red-800">delete
                            </button>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </x-payu::paper>
@endsection

