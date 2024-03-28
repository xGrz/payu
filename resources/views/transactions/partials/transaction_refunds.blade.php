@if ($transaction->status->hasAction('refund'))
    <x-payu::paper class="bg-slate-800 mt-4">
        <x-payu::paper-title title="Refunds to transaction">
            <x-payu::buttonlink href="{{route('payu.refunds.create', $transaction->id)}}">
                Create refund
            </x-payu::buttonlink>
        </x-payu::paper-title>

        @if ($transaction->refunds->count())
            <x-payu::table>
                <x-payu::table.thead>
                    <x-payu::table.row>
                        <x-payu::table.header class="text-left">Description</x-payu::table.header>
                        <x-payu::table.header>RefundId</x-payu::table.header>
                        <x-payu::table.header>ExtOrderId</x-payu::table.header>
                        <x-payu::table.header class="text-left">Bank description</x-payu::table.header>
                        <x-payu::table.header class="text-left">Status</x-payu::table.header>
                        <x-payu::table.header class="text-right">Amount</x-payu::table.header>
                        <x-payu::table.header class="text-right">Date</x-payu::table.header>
                        <td></td>
                    </x-payu::table.row>
                </x-payu::table.thead>
                <tbody>
                @foreach($transaction->refunds as $refund)
                    <x-payu::table.row>
                        <x-payu::table.cell>{{ $refund->description }}</x-payu::table.cell>
                        <x-payu::table.cell>{{ $refund->refund_id }}</x-payu::table.cell>
                        <x-payu::table.cell>{{ $refund->ext_refund_id }}</x-payu::table.cell>
                        <x-payu::table.cell>{{ $refund->bank_description }}</x-payu::table.cell>
                        <x-payu::table.cell>
                            <x-payu::status :status="$refund->status"/>
                        </x-payu::table.cell>
                        <x-payu::table.cell
                            class="text-right">{{ $refund->amount }} {{ $refund->currency_code }}</x-payu::table.cell>
                        <x-payu::table.cell class="text-right">{{ $refund->created_at }}</x-payu::table.cell>
                        <x-payu::table.cell class="text-right">
                            @if($refund->status->hasAction('delete'))
                                <form action="{{ route('payu.refunds.destroy', $refund->id) }}" method="POST"
                                      id="delete_refund_{{$refund->id}}">
                                    @csrf @method('DELETE')
                                </form>
                                <x-payu::button class="text-red-500" form="delete_refund_{{$refund->id}}" size="small"
                                                color="danger" type="submit">Delete
                                </x-payu::button>
                            @endif
                        </x-payu::table.cell>
                    </x-payu::table.row>
                @endforeach
                </tbody>
            </x-payu::table>
            <div class="text-gray-500 my-1">
                Refunds are dispatched to operator every 30 minutes. Once refund is sent you cannot revoke it.
            </div>
        @else
            <x-payu::not-found message="Refunds not found"/>
        @endif


    </x-payu::paper>
@endif
