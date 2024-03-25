@extends('payu::app')

@section('content')
    Transaction content:
    <x-payu::paper>
        <table class="w-full">
            <thead>
            <tr>
                <th class="text-left">Name</th>
                <th class="text-right">Unit price</th>
                <th class="text-right">Quantity</th>
                <th class="text-right">Value</th>
            </tr>
            </thead>
            <tbody>
            @foreach($products as $product)
                <tr>
                    <td>{{ $product['name'] }}</td>
                    <td class="text-right">{{ $product['unitPrice'] / 100 }}</td>
                    <td class="text-right">{{ $product['quantity'] }}</td>
                    <td class="text-right">{{ $product['unitPrice'] / 100 * $product['quantity'] }}</td>
                </tr>
            @endforeach
            </tbody>
            <tfoot>
            <tr>
                <td colspan="4" class="text-right font-bold">{{ $transactionAmount }}</td>
            </tr>
            </tfoot>
        </table>
    </x-payu::paper>

    @include('payu::transactions.partials.transaction_refunds')

    <div class="py-5">
        <hr/>
    </div>


    <div class="mt-2"></div>
    Prepare refund:
    <form action="{{route('payu.refunds.store', $transaction->id)}}" method="POST">
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
        <div class="p-1 bg-red-100 border border-red-300 rounded flex justify-between items-center">
            <strong class="text-red-700">Confirm your refund request</strong>
            <x-payu::button severity="info" size="small" type="submit">
                Make a refund
            </x-payu::button>
        </div>
    </form>
@endsection
