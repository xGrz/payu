<div wire:poll.keep-alive.5s>
    @php use \xGrz\PayU\Facades\Config; @endphp
    @props(['tableTitle' => '[tableTitle] not set', 'shouldRenderAction' => false])

    @if ($transaction->status->hasAction('refund'))
        <x-p-paper class="bg-slate-800 mt-4">
            <x-slot:title>{{ $tableTitle ?? 'Upss, no title set' }}</x-slot:title>
            @if($shouldRenderAction && $transaction->isRefundAvailable())
                <x-slot:actions>
                    <x-p-button
                        wire:click="$dispatch('openModal', { component: 'payu-refund-create-form', arguments: { transaction: '{{ $transaction->id }}' } })">
                        Create refund
                    </x-p-button>
                </x-slot:actions>
            @endif

            @if ($transaction->refunds->count())
                <x-p-table>
                    <x-p-thead>
                        <x-p-tr>
                            <x-p-th class="text-left">Description</x-p-th>
                            <x-p-th>RefundId</x-p-th>
                            <x-p-th class="text-left">Bank description</x-p-th>
                            <x-p-th class="text-left">Status</x-p-th>
                            <x-p-th class="text-right">Amount</x-p-th>
                            <x-p-th class="text-right">Date</x-p-th>
                            <td></td>
                        </x-p-tr>
                    </x-p-thead>
                    <x-p-tbody>
                        @foreach($transaction->refunds as $refund)
                            <x-p-tr>
                                <x-p-td>{{ $refund->description }}</x-p-td>
                                <x-p-td>{{ $refund->refund_id }}</x-p-td>
                                <x-p-td>{{ $refund->bank_description }}</x-p-td>
                                <x-p-td>
                                    <x-p-status :status="$refund->status"/>
                                    @if($refund->error)
                                        <small class="block">{{ $refund->errorDescription }}</small>
                                    @endif
                                </x-p-td>
                                <x-p-td
                                    class="text-right">{{ humanAmount($refund->amount, $refund->currency_code) }}</x-p-td>
                                <x-p-td class="text-right">{{ $refund->created_at }}</x-p-td>
                                <x-p-td class="text-right">
                                    @if($refund->status->hasAction('delete'))
                                        <x-p-button
                                            size="small"
                                            color="danger"
                                            wire:click="deleteRefund({{$refund->id}})"
                                        >
                                            Delete
                                        </x-p-button>
                                    @endif
                                    @if($refund->status->hasAction('retry'))
                                        <x-p-button
                                            size="small"
                                            wire:click="retryRefund({{$refund->id}})"
                                        >
                                            Retry
                                        </x-p-button>
                                    @endif
                                </x-p-td>
                            </x-p-tr>
                        @endforeach
                    </x-p-tbody>
                    <x-p-tfoot>
                        <x-p-tr>
                            <x-p-td right colspan="4">Refunded</x-p-td>
                            <x-p-td right>
                                <strong>{{ humanAmount($transaction->refundedAmount(), $refund->currency_code) }}</strong>
                            </x-p-td>
                        </x-p-tr>
                    </x-p-tfoot>
                </x-p-table>
                <div class="text-gray-500 my-1 mx-2">
                    Once refund is sent you cannot revoke it.
                </div>
            @else
                <x-p-not-found message="Refunds not found"/>
            @endif

        </x-p-paper>
    @endif

</div>
