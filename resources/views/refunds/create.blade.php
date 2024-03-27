@extends('payu::app')

@section('content')
    <x-payu::paper class="bg-slate-800 pb-2 mb-4">
        <x-payu::paper-title title="Prepare refund"/>
        <form action="{{route('payu.refunds.store', $transaction->id)}}" method="POST" class="px-2">
            @csrf
            <div class="grid grid-cols-3 gap-2">
                <div>
                    <x-payu::input
                        type="number"
                        name="amount"
                        step="0.01"
                        max="{{ $transactionAmount - $refunded }}"
                        value="{{ $transactionAmount - $refunded }}"
                        label="Amount"
                    />
                </div>
                <div>
                    <x-payu::input
                        label="Description (reason)"
                        name="description"
                    />
                </div>
                <div>
                    <x-payu::input
                        label="Bank description"
                        name="bankDescription"
                    />
                </div>
            </div>
            <div class="text-right">
                <x-payu::button color="success" size="large" type="submit">
                    Send refund request
                </x-payu::button>
            </div>
        </form>

    </x-payu::paper>


    <x-payu::paper class="bg-slate-800">
        <x-payu::paper-title title="Transaction content"/>

        <x-payu::table>
            <x-payu::table.thead>
                <x-payu::table.row>
                    <x-payu::table.header class="text-left">Name</x-payu::table.header>
                    <x-payu::table.header class="text-right">Unit price</x-payu::table.header>
                    <x-payu::table.header class="text-right">Quantity</x-payu::table.header>
                    <x-payu::table.header class="text-right">Value</x-payu::table.header>
                </x-payu::table.row>
            </x-payu::table.thead>
            <tbody>
            @foreach($products as $product)
                <x-payu::table.row>
                    <x-payu::table.cell>{{ $product['name'] }}</x-payu::table.cell>
                    <x-payu::table.cell class="text-right">{{ $product['unitPrice'] / 100 }}</x-payu::table.cell>
                    <x-payu::table.cell class="text-right">{{ $product['quantity'] }}</x-payu::table.cell>
                    <x-payu::table.cell class="text-right">{{ $product['unitPrice'] / 100 * $product['quantity'] }}</x-payu::table.cell>
                </x-payu::table.row>
            @endforeach
            </tbody>
            <tfoot>
            <tr>
                <x-payu::table.cell colspan="4" class="text-right font-bold">
                    {{ $transactionAmount }}
                </x-payu::table.cell>
            </tr>
            </tfoot>
        </x-payu::table>
    </x-payu::paper>

    @include('payu::transactions.partials.transaction_refunds')

@endsection
