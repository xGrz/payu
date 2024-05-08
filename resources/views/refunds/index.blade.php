@php use \xGrz\PayU\Facades\Config; @endphp
@extends('p::app')

@section('content')
    <x-p-pagination info-only :source="$refunds"/>
    <x-p-paper>
        <x-slot:title>Created refunds</x-slot:title>
        @if($refunds->count())
            <x-p-table class="w-full">
                <x-p-table>
                    <x-p-tr>
                        <x-p-th class="text-left">Transaction</x-p-th>
                        <x-p-th class="text-left">Description</x-p-th>
                        <x-p-th class="text-left">Bank description</x-p-th>
                        <x-p-th class="text-right">Amount</x-p-th>
                        <x-p-th class="text-right">Status</x-p-th>
                    </x-p-tr>
                </x-p-table>
                <x-p-tbody>
                    @foreach($refunds as $refund)
                        <x-p-tr>
                            <x-p-td>
                                <x-p-link
                                    href="{{route(Config::getRouteName('payments.show'), $refund->transaction->id)}}">
                                    {{$refund->transaction->payload['description']}}
                                </x-p-link>
                            </x-p-td>
                            <x-p-td>{{$refund->description}}</x-p-td>
                            <x-p-td>{{$refund->bankDescription}}</x-p-td>
                            <x-p-td class="text-right">{{ humanAmount($refund->amount)}}</x-p-td>
                            <x-p-td class="text-right">
                                <x-p-status :status="$refund->status"/>
                            </x-p-td>
                        </x-p-tr>
                    @endforeach
                </x-p-tbody>
            </x-p-table>
            <div class="py-2">
                <x-p-pagination :source="$refunds"/>
            </div>
        @else
            <x-p-not-found message="Refunds not found"/>
        @endif

    </x-p-paper>
@endsection
