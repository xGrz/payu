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
    {{--    @if($balance)--}}
    {{--        @include('payu::balance.balance')--}}
    {{--    @endif--}}
    <x-payu::paper class="bg-slate-800">
        <x-payu::table.title title="Transactions listing">
            <form action="{{route('payu.payments.store')}}" method="POST" id="createTransaction">
                @csrf
            </form>
            <x-payu::button type="submit" form="createTransaction" color="success">
                Create fake payment
            </x-payu::button>
            <x-payu::buttonlink href="https://merch-prod.snd.payu.com/user/login?lang=pl" target="new" color="warning">
                PayU-Panel
            </x-payu::buttonlink>
        </x-payu::table.title>

        <x-payu::table>
            <x-payu::table.thead class="text-left text-white leading-8">
                <x-payu::table.row>
                    <x-payu::table.header>Description</x-payu::table.header>
                    <x-payu::table.header>Value</x-payu::table.header>
                    <x-payu::table.header>Status</x-payu::table.header>
                    <x-payu::table.header class="text-right">Created</x-payu::table.header>
                    <x-payu::table.header class="text-right">Updated</x-payu::table.header>
                    <x-payu::table.header>Actions</x-payu::table.header>
                </x-payu::table.row>
            </x-payu::table.thead>
            <tbody class="leading-8">
            @foreach($transactions as $transaction)
                <x-payu::table.row class="hover:bg-gray-100">
                    <x-payu::table.cell>
                        @if($transaction->status === PaymentStatus::INITIALIZED)
                            <span class="text-gray-300">{{ $transaction->payload['description']  }}</span>
                        @else
                            <x-payu::link
                                href="{{ route('payu.payments.show', $transaction->id )}}"
                            >
                                {{ $transaction->payload['description']  }}
                            </x-payu::link>
                        @endif
                    </x-payu::table.cell>
                    <x-payu::table.cell class="text-right">
                        {{ Number::currency($transaction->payload['totalAmount'] / 100, $transaction->payload['currencyCode'], 'pl') }}
                    </x-payu::table.cell>
                    <x-payu::table.cell>
                        <x-payu::status :status="$transaction->status" class="text-sm mx-2"/>
                    </x-payu::table.cell>
                    <x-payu::table.cell class="text-right">{{ $transaction->created_at }}</x-payu::table.cell>
                    <x-payu::table.cell
                        class="text-right">{{ $transaction->created_at == $transaction->updated_at ? '' : $transaction->updated_at}}</x-payu::table.cell>
                    <x-payu::table.cell>
                        @if($transaction->status->actionAvailable('pay'))
                            <x-payu::buttonlink href="{!! $transaction->link !!}" target="new" size="small">
                                Pay
                            </x-payu::buttonlink>
                        @endif
                        @if($transaction->status->actionAvailable('delete'))
                            <form action="{{route('payu.payments.destroy', $transaction->id)}}" method="POST"
                                  id="delete_{{$transaction->id}}">
                                @csrf @method('DELETE')
                            </form>
                            <x-payu::button type="submit" form="delete_{{$transaction->id}}" size="small" color="danger">Delete
                            </x-payu::button>
                        @endif
                    </x-payu::table.cell>
                </x-payu::table.row>
            @endforeach
            </tbody>
        </x-payu::table>
    </x-payu::paper>
@endsection

