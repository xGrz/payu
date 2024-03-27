@php use Illuminate\Support\Number; @endphp
@extends('payu::app')

@section('breadcrumbs')
    @if($balance)
        {{ $balance->name }}
    @endif
@endsection

@section('content')
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <x-payu::paper class="bg-slate-800">
            <x-payu::paper-title title="PayU account balance"/>
            <x-payu::table>
                <tbody>
                <tr>
                    <td>Balance</td>
                    <td>{{ $balance->humanAmount->balance}}</td>
                </tr>
                <tr>
                    <td>Reserved</td>
                    <td>{{ $balance->humanAmount->reserved}}</td>
                </tr>
                <tr>
                    <td>Available</td>
                    <td>{{ $balance->humanAmount->available}}</td>
                </tr>
                </tbody>
            </x-payu::table>
        </x-payu::paper>
        <x-payu::paper class="bg-slate-800">
            @include('payu::payouts.payout-form')
        </x-payu::paper>
    </div>

    @empty($payouts->toArray())
        <span class="block text-gray-500 mt-2">Payouts not found</span>
    @else
        <x-payu::paper class="bg-slate-800 mt-4">
            <x-payu::paper-title title="Payout listing"/>
            <x-payu::table class="w-full">
                <x-payu::table.thead>
                    <x-payu::table.row>
                        <x-payu::table.header class="text-left">Payout ordered at</x-payu::table.header>
                        <x-payu::table.header class="text-right">Amount</x-payu::table.header>
                        <x-payu::table.header class="text-right">Status</x-payu::table.header>
                        <x-payu::table.header class="text-right"></x-payu::table.header>
                    </x-payu::table.row>
                </x-payu::table.thead>
                <tbody>
                @foreach($payouts as $payout)
                    <x-payu::table.row>
                        <x-payu::table.cell>{{ $payout->created_at }}</x-payu::table.cell>
                        <x-payu::table.cell class="text-right">{{ Number::currency($payout->amount, 'PLN', 'pl') }}</x-payu::table.cell>
                        <x-payu::table.cell class="text-right">
                            <x-payu::status :status="$payout->status"/>
                        </x-payu::table.cell>
                        <x-payu::table.cell class="text-right">
                            @if($payout->status->actionAvailable('delete'))
                                <form action="{{route('payu.payouts.destroy', $payout->id)}}" method="POST"
                                      id="payout_{{$payout->id}}">
                                    @csrf @method('DELETE')
                                </form>
                                <x-payu::button type="submit" color="danger" form="payout_{{$payout->id}}" size="small">
                                    Delete
                                </x-payu::button>
                            @endif
                        </x-payu::table.cell>
                    </x-payu::table.row>
                @endforeach
                </tbody>
            </x-payu::table>
        </x-payu::paper>
    @endif

@endsection
