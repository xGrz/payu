<x-payu::paper>
    <table class="w-full">
        <tr>
            <td>Transaction id</td>
            <td>{{$transaction->id}}</td>
        </tr>
        <tr>
            <td>PayU order id</td>
            <td>{{$transaction->payu_order_id}}</td>
        </tr>
        <tr>
            <td>Payment link</td>
            <td>
                <x-payu::link href="{!! $transaction->link !!}">Payment link</x-payu::link>
            </td>
        </tr>
        <tr>
            <td>Status</td>
            <td><x-payu::status :status="$transaction->status" class="text-lg py-1 px-2"/></td>
        </tr>
        <tr>
            <td>PayMethod</td>
            <td>
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
            </td>
        </tr>
        <tr>
            <td>Amount</td>
            <td>{{ Number::currency($transaction->payload['totalAmount'] / 100, $transaction->payload['currencyCode'], app()->currentLocale()) }}</td>
        </tr>
        <tr>
            <td>Refunded</td>
            <td>
                {{ Number::currency($transaction->refunded(), $transaction->payload['currencyCode'], app()->currentLocale()) }}
            </td>
        </tr>
        <tr>
            <td>Description</td>
            <td>
                {{ $transaction->payload['description'] }}
                @if (!empty($transaction->payload['additionalDescription']))
                    <hr/>
                    {{ $transaction->payload['additionalDescription'] }}
                @endif
                @if (!empty($transaction->payload['visibleDescription']))
                    <hr/>
                    {{ $transaction->payload['visibleDescription'] }}
                @endif
            </td>
        </tr>
    </table>

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
        <x-payu::button form="reject" type="submit" color="danger">Reject payment</x-payu::button>
    @endif
</x-payu::paper>
