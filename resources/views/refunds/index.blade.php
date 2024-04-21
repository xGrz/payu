@php use \xGrz\PayU\Facades\Config; @endphp
@extends('p::app')

@section('content')
    <x-p::pagination.info :source="$refunds"/>
    <x-p::paper class="bg-slate-800">
        <x-p::paper-title title="Created refunds"/>
        @if($refunds->count())
            <x-p::table class="w-full">
                <x-p::table.thead>
                    <x-p::table.row>
                        <x-p::table.thead class="text-left">Transaction</x-p::table.th>
                        <x-p::table.th class="text-left">Description</x-p::table.th>
                        <x-p::table.th class="text-left">Bank description</x-p::table.th>
                        <x-p::table.th class="text-right">Amount</x-p::table.th>
                        <x-p::table.th class="text-right">Status</x-p::table.th>
                    </x-p::table.row>
                </x-p::table.thead>
                <tbody>
                @foreach($refunds as $refund)
                    <x-p::table.row>
                        <x-p::table.cell>
                            <x-p::link href="{{route(Config::getRouteName('payments.show'), $refund->transaction->id)}}">
                                {{$refund->transaction->payload['description']}}
                            </x-p::link>
                        </x-p::table.cell>
                        <x-p::table.cell>{{$refund->description}}</x-p::table.cell>
                        <x-p::table.cell>{{$refund->bankDescription}}</x-p::table.cell>
                        <x-p::table.cell class="text-right">{{ humanAmount($refund->amount)}}</x-p::table.cell>
                        <x-p::table.cell class="text-right">
                            <x-p::status :status="$refund->status"/>
                        </x-p::table.cell>
                    </x-p::table.row>
                @endforeach
                </tbody>
            </x-p::table>
            <div class="py-2">
                <x-p::pagination :source="$refunds"/>
            </div>
        @else
            <x-p::not-found message="Refunds not found"/>
        @endif

    </x-p::paper>
@endsection
