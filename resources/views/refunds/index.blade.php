@extends('payu::app')

@section('content')
    <x-payu::paper class="bg-white">
        <table class="w-full">
            <thead>
            <tr>
                <th class="text-left">Transaction</th>
                <th class="text-left">Description</th>
                <th class="text-left">Bank description</th>
                <th class="text-right">Amount</th>
                <th class="text-right">Status</th>
            </tr>
            </thead>
            <tbody>
            @foreach($refunds as $refund)
                <tr>
                    <td>
                        <x-payu::link href="{{route('payu.payments.show', $refund->transaction->id)}}">
                            {{$refund->transaction->payload['description']}}
                        </x-payu::link>
                    </td>
                    <td>{{$refund->description}}</td>
                    <td>{{$refund->bankDescription}}</td>
                    <td class="text-right">{{$refund->amount}}</td>
                    <td class="text-right">
                        <x-payu::status :status="$refund->status"/>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </x-payu::paper>
@endsection
