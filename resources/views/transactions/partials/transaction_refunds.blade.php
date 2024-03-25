
@if ($transaction->refunds->count())
    Refunds:
    <x-payu::paper>
        <table class="w-full">
            <thead>
            <tr>
                <th class="text-left">Description</th>
                <th>RefundId</th>
                <th>ExtOrderId</th>
                <th class="text-left">Bank description</th>
                <th class="text-left">Status</th>
                <th class="text-right">Amount</th>
                <th class="text-right">Date</th>
                <td></td>
            </tr>
            </thead>
            <tbody>
            @foreach($transaction->refunds as $refund)
                <tr>
                    <td>{{ $refund->description }}</td>
                    <td>{{ $refund->refund_id }}</td>
                    <td>{{ $refund->ext_refund_id }}</td>
                    <td>{{ $refund->bank_description }}</td>
                    <td class="{{ $refund->status->color() }}">{{ $refund->status->name }}</td>
                    <td class="text-right">{{ $refund->amount }} {{ $refund->currency_code }}</td>
                    <td class="text-right">{{ $refund->created_at }}</td>
                    <td>
                        @if($refund->status->isDeletable())
                            <form action="{{ route('payu.refunds.destroy', $refund->id) }}" method="POST"
                                  id="delete_refund_{{$refund->id}}">
                                @csrf @method('DELETE')
                            </form>
                            <button class="text-red-500" form="delete_refund_{{$refund->id}}" type="submit">Delete
                            </button>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </x-payu::paper>
    <div class="text-gray-500 my-1">
        Refunds are dispatched to operator every 30 minutes. Once refund is sent you cannot revoke it.
    </div>
@else
    <div>
    No refunds to transaction
    </div>
@endif