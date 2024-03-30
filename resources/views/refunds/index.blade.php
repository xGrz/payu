@extends('payu::app')

@section('content')
    <x-payu::pagination.info :source="$refunds"/>
    <x-payu::paper class="bg-slate-800">
        <x-payu::paper-title title="Created refunds"/>
        @if($refunds->count())
            <x-payu::table class="w-full">
                <x-payu::table.thead>
                    <x-payu::table.row>
                        <x-payu::table.header class="text-left">Transaction</x-payu::table.header>
                        <x-payu::table.header class="text-left">Description</x-payu::table.header>
                        <x-payu::table.header class="text-left">Bank description</x-payu::table.header>
                        <x-payu::table.header class="text-right">Amount</x-payu::table.header>
                        <x-payu::table.header class="text-right">Status</x-payu::table.header>
                    </x-payu::table.row>
                </x-payu::table.thead>
                <tbody>
                @foreach($refunds as $refund)
                    <x-payu::table.row>
                        <x-payu::table.cell>
                            <x-payu::link href="{{route('payu.payments.show', $refund->transaction->id)}}">
                                {{$refund->transaction->payload['description']}}
                            </x-payu::link>
                        </x-payu::table.cell>
                        <x-payu::table.cell>{{$refund->description}}</x-payu::table.cell>
                        <x-payu::table.cell>{{$refund->bankDescription}}</x-payu::table.cell>
                        <x-payu::table.cell class="text-right">{{ humanAmount($refund->amount)}}</x-payu::table.cell>
                        <x-payu::table.cell class="text-right">
                            <x-payu::status :status="$refund->status"/>
                        </x-payu::table.cell>
                    </x-payu::table.row>
                @endforeach
                </tbody>
            </x-payu::table>
            <div class="py-2">
                <x-payu::pagination :source="$refunds"/>
            </div>
        @else
            <x-payu::not-found message="Refunds not found"/>
        @endif

    </x-payu::paper>
@endsection
