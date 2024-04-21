@php use \xGrz\PayU\Facades\Config; @endphp
<x-p::paper class="bg-slate-800">
    <x-p::paper-title title="Transaction details">
        <form action="{{route(Config::getRouteName('payments.accept'), $transaction->id)}}" method="POST" id="accept">
            @method('PATCH')
            @csrf
        </form>
        <form action="{{route(Config::getRouteName('.payments.reject'), $transaction->id)}}" method="POST" id="reject">
            @method('DELETE')
            @csrf
        </form>

        @if($transaction->status->hasAction('delete'))
            <x-p::button form="reject" type="submit" color="danger">Delete transaction</x-p::button>
        @endif

        @if($transaction->status->hasAction('accept'))
            <x-p::button form="accept" type="submit" color="success">Accept</x-p::button>
        @endif
        @if($transaction->status->hasAction('reject'))
            <x-p::button form="reject" type="submit" color="danger">Reject</x-p::button>
        @endif
    </x-p::paper-title>
    <x-p::table class="p-2">
        <x-p::table.row>
            <x-p::table.th class="text-left">Transaction id</x-p::table.th>
            <x-p::table.cell>{{$transaction->id}}</x-p::table.cell>
        </x-p::table.row>
        <x-p::table.row>
            <x-p::table.th class="text-left">PayU order id</x-p::table.th>
            <x-p::table.cell>{{$transaction->payu_order_id}}</x-p::table.cell>
        </x-p::table.row>
        <x-p::table.row>
            <x-p::table.th class="text-left">Payment link</x-p::table.th>
            <x-p::table.cell>
                <x-p::link href="{!! $transaction->link !!}">Payment link</x-p::link>
            </x-p::table.cell>
        </x-p::table.row>
        <x-p::table.row>
            <x-p::table.th class="text-left">Status</x-p::table.th>
            <x-p::table.cell>
                <x-p::status :status="$transaction->status" class="text-lg py-1 px-2"/>
            </x-p::table.cell>
        </x-p::table.row>
        <x-p::table.row>
            <x-p::table.th class="text-left">PayMethod</x-p::table.th>
            <x-p::table.cell>
                @if (empty($transaction->payMethod))
                    No transaction details received yet.
                    @if($transaction->status->hasAction('payMethod'))
                        <x-p::link href="{{ route('payu.payments.method', $transaction->id) }}">Get pay method now.
                        </x-p::link>
                    @endif
                @else
                    {{ $transaction->payMethod->name }}
                    <img
                            src="{!! $transaction->payMethod->image !!}"
                            alt="{{ $transaction->payMethod->name }}"
                            class="max-h-10 max-w-16 fill bg-white p-2"
                    />
                @endif
            </x-p::table.cell>
        </x-p::table.row>
        <x-p::table.row>
            <x-p::table.th class="text-left">Amount</x-p::table.th>
            <x-p::table.cell>{{ humanAmount($transaction->payload['totalAmount'] / 100, $transaction->payload['currencyCode']) }}</x-p::table.cell>
        </x-p::table.row>
        <x-p::table.row>
            <x-p::table.th class="text-left">Refunded</x-p::table.th>
            <x-p::table.cell>
                {{ humanAmount($transaction->refundedAmount(), $transaction->payload['currencyCode']) }}
            </x-p::table.cell>
        </x-p::table.row>
        <x-p::table.row>
            <x-p::table.th class="text-left">Description</x-p::table.th>
            <x-p::table.cell>{{ $transaction->payload['description'] }}</x-p::table.cell>
        </x-p::table.row>
        @if (!empty($transaction->payload['additionalDescription']) && $transaction->payload['additionalDescription'] !== $transaction->payload['description'])
            <x-p::table.row>
                <x-p::table.th class="text-left">Additional description</x-p::table.th>
                <x-p::table.cell>{{ $transaction->payload['additionalDescription'] }}</x-p::table.cell>
            </x-p::table.row>
        @endif
        @if (!empty($transaction->payload['visibleDescription']) && $transaction->payload['visibleDescription'] !== $transaction->payload['description'])
            <x-p::table.row>
                <x-p::table.th class="text-left">User visible description</x-p::table.th>
                <x-p::table.cell>{{ $transaction->payload['visibleDescription'] }}</x-p::table.cell>
            </x-p::table.row>
        @endif
    </x-p::table>
</x-p::paper>
