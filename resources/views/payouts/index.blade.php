@php use Illuminate\Support\Number; @endphp
@extends('payu::app')

@section('breadcrumbs')
    @if($balance)
        {{ $balance->name }}
    @endif
@endsection

@section('content')
    <div class="grid grid-cols-6 gap-2">
        <x-payu::paper class="col-span-6 sm:col-span-3 md:col-span-4 bg-white">
            <table class="w-full">
                <tbody>
                <tr>
                    <th class="text-left">Balance</th>
                    <td class="text-right">{{ $balance->humanAmount->balance}}</td>
                </tr>
                <tr>
                    <th class="text-left">Reserve for refunds</th>
                    <td class="text-right">{{ $balance->humanAmount->reserved}}</td>
                </tr>
                <tr>
                    <th class="text-left">Available</th>
                    <td class="text-right">{{ $balance->humanAmount->available}}</td>
                </tr>
                </tbody>
            </table>
        </x-payu::paper>
        <x-payu::paper class="bg-white col-span-6 sm:col-span-3 md:col-span-2">
            @include('payu::payouts.payout-form')
        </x-payu::paper>
    </div>

    @empty($payouts->toArray())
        <span class="block text-gray-500 mt-2">Payouts not found</span>
    @else
        Payout listing
        <x-payu::paper class="bg-white">
            <table class="w-full">
                <thead>
                <tr>
                    <th class="text-left">Payout ordered at</th>
                    <th class="text-right">Amount</th>
                    <th class="text-right">Status</th>
                    <th class="text-right"></th>
                </tr>
                </thead>
                <tbody>
                @foreach($payouts as $payout)
                    <tr>
                        <td>{{ $payout->created_at }}</td>
                        <td class="text-right">{{ Number::currency($payout->amount, 'PLN', 'pl') }}</td>
                        <td class="text-right">
                            <x-payu::status :status="$payout->status"/>
                        </td>
                        <td class="text-right">
                            @if($payout->status->actionAvailable('delete'))
                                <form action="{{route('payu.payouts.destroy', $payout->id)}}" method="POST" id="payout_{{$payout->id}}">
                                    @csrf @method('DELETE')
                                </form>
                                <x-payu::button type="submit" color="danger" form="payout_{{$payout->id}}" size="small">Delete</x-payu::button>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </x-payu::paper>
    @endif

@endsection
