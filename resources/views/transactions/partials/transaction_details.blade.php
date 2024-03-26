<x-payu::paper class="bg-slate-800">
    <x-payu::table.title title="Transaction details">
        <form action="{{route('payu.payments.accept', $transaction->id)}}" method="POST" id="accept">
            @method('PATCH')
            @csrf
        </form>
        <form action="{{route('payu.payments.reject', $transaction->id)}}" method="POST" id="reject">
            @method('DELETE')
            @csrf
        </form>

        @if($transaction->status->actionAvailable('delete'))
            <x-payu::button form="reject" type="submit" color="danger">Delete transaction</x-payu::button>
        @endif

        @if($transaction->status->actionAvailable('accept'))
            <x-payu::button form="accept" type="submit" color="success">Accept</x-payu::button>
        @endif
        @if($transaction->status->actionAvailable('reject'))
            <x-payu::button form="reject" type="submit" color="danger">Reject</x-payu::button>
        @endif
    </x-payu::table.title>
    <x-payu::table  class="p-2">
        <x-payu::table.row>
            <x-payu::table.header class="text-left">Transaction id</x-payu::table.header>
            <x-payu::table.cell>{{$transaction->id}}</x-payu::table.cell>
        </x-payu::table.row>
        <x-payu::table.row>
            <x-payu::table.header class="text-left">PayU order id</x-payu::table.header>
            <x-payu::table.cell>{{$transaction->payu_order_id}}</x-payu::table.cell>
        </x-payu::table.row>
        <x-payu::table.row>
            <x-payu::table.header class="text-left">Payment link</x-payu::table.header>
            <x-payu::table.cell>
                <x-payu::link href="{!! $transaction->link !!}">Payment link</x-payu::link>
            </x-payu::table.cell>
        </x-payu::table.row>
        <x-payu::table.row>
            <x-payu::table.header class="text-left">Status</x-payu::table.header>
            <x-payu::table.cell>
                <x-payu::status :status="$transaction->status" class="text-lg py-1 px-2"/>
            </x-payu::table.cell>
        </x-payu::table.row>
        <x-payu::table.row>
            <x-payu::table.header class="text-left">PayMethod</x-payu::table.header>
            <x-payu::table.cell>
                @if (empty($transaction->payMethod))
                    No transaction details received yet
                @else
                    {{ $transaction->payMethod->name }}
                    <img
                        src="{!! $transaction->payMethod->image !!}"
                        alt="{{ $transaction->payMethod->name }}"
                        class="max-h-8 max-w-16 fill"
                    />
                @endif
            </x-payu::table.cell>
        </x-payu::table.row>
        <x-payu::table.row>
            <x-payu::table.header class="text-left">Amount</x-payu::table.header>
            <x-payu::table.cell>{{ Number::currency($transaction->payload['totalAmount'] / 100, $transaction->payload['currencyCode'], app()->currentLocale()) }}</x-payu::table.cell>
        </x-payu::table.row>
        <x-payu::table.row>
            <x-payu::table.header class="text-left">Refunded</x-payu::table.header>
            <x-payu::table.cell>
                {{ Number::currency($transaction->refunded(), $transaction->payload['currencyCode'], app()->currentLocale()) }}
            </x-payu::table.cell>
        </x-payu::table.row>
        <x-payu::table.row>
            <x-payu::table.header class="text-left">Description</x-payu::table.header>
            <x-payu::table.cell>
                {{ $transaction->payload['description'] }}
                @if (!empty($transaction->payload['additionalDescription']))
                    <hr/>
                    {{ $transaction->payload['additionalDescription'] }}
                @endif
                @if (!empty($transaction->payload['visibleDescription']))
                    <hr/>
                    {{ $transaction->payload['visibleDescription'] }}
                @endif
            </x-payu::table.cell>
        </x-payu::table.row>
    </x-payu::table>
</x-payu::paper>
