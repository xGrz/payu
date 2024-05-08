@php use \xGrz\PayU\Facades\Config; @endphp
@props(['tableTitle' => '[tableTitle] not set', 'shouldRenderAction' => false])

@if ($transaction->status->hasAction('refund'))
    <x-p-paper class="bg-slate-800 mt-4">
        <x-slot:title>{{ $tableTitle ?? 'Upss, no title set' }}</x-slot:title>

        @if($shouldRenderAction && $transaction->isRefundAvailable())
            <x-slot:actions>
                @include('payu::refunds.create-refund')
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
                <tbody>
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
                                <form action="{{ route(Config::getRouteName('refunds.destroy'), $refund->id) }}"
                                      method="POST"
                                      id="delete_refund_{{$refund->id}}">
                                    @csrf @method('DELETE')
                                </form>
                                <x-p-button class="text-red-500" form="delete_refund_{{$refund->id}}" size="small"
                                             color="danger" type="submit">Delete
                                </x-p-button>
                            @endif
                            @if($refund->status->hasAction('retry'))
                                <x-p-button href="{{route(Config::getRouteName('refunds.retry'), $refund->id)}}">
                                    Retry
                                </x-p-button>
                            @endif
                        </x-p-td>
                    </x-p-tr>
                @endforeach
                </tbody>
            </x-p-table>
            <div class="text-gray-500 my-1 mx-2">
                Once refund is sent you cannot revoke it.
            </div>
        @else
            <x-p-not-found message="Refunds not found"/>
        @endif

    </x-p-paper>
@endif
