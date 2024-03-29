@extends('payu::app')

@section('content')

    @include('payu::refunds.create-refund')

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
                    <x-payu::table.cell
                            class="text-right">{{ humanAmount($product['unitPrice'] / 100) }}</x-payu::table.cell>
                    <x-payu::table.cell class="text-right">{{ $product['quantity'] }}</x-payu::table.cell>
                    <x-payu::table.cell
                            class="text-right">{{humanAmount($product['unitPrice'] / 100 * $product['quantity'])}}</x-payu::table.cell>
                </x-payu::table.row>
            @endforeach
            </tbody>
            <tfoot>
            <tr>
                <x-payu::table.cell colspan="4" class="text-right font-bold">
                    {{humanAmount($transactionAmount) }}
                </x-payu::table.cell>
            </tr>
            </tfoot>
        </x-payu::table>
    </x-payu::paper>

    @include('payu::transactions.partials.transaction_refunds', ['tableTitle' => 'Already refunded'])

@endsection
