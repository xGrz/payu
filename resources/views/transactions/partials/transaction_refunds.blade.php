@php use \xGrz\PayU\Facades\Config; @endphp
@props(['tableTitle' => '[tableTitle] not set', 'shouldRenderAction' => false])

@if ($transaction->status->hasAction('refund'))
    <x-p::paper class="bg-slate-800 mt-4">
        <x-p::paper-title title="{{ $tableTitle ?? 'Upss, no title set' }}">
            @if($shouldRenderAction && $transaction->isRefundAvailable())
                @include('payu::refunds.create-refund')
            @endif
        </x-p::paper-title>

        @if ($transaction->refunds->count())
            <x-p::table>
                <x-p::table.thead>
                    <x-p::table.row>
                        <x-p::table.th class="text-left">Description</x-p::table.th>
                        <x-p::table.th>RefundId</x-p::table.th>
                        <x-p::table.th class="text-left">Bank description</x-p::table.th>
                        <x-p::table.th class="text-left">Status</x-p::table.th>
                        <x-p::table.th class="text-right">Amount</x-p::table.th>
                        <x-p::table.th class="text-right">Date</x-p::table.th>
                        <td></td>
                    </x-p::table.row>
                </x-p::table.thead>
                <tbody>
                @foreach($transaction->refunds as $refund)
                    <x-p::table.row>
                        <x-p::table.cell>{{ $refund->description }}</x-p::table.cell>
                        <x-p::table.cell>{{ $refund->refund_id }}</x-p::table.cell>
                        <x-p::table.cell>{{ $refund->bank_description }}</x-p::table.cell>
                        <x-p::table.cell>
                            <x-p::status :status="$refund->status"/>
                            @if($refund->error)
                                <small class="block">{{ $refund->errorDescription }}</small>
                            @endif
                        </x-p::table.cell>
                        <x-p::table.cell
                            class="text-right">{{ humanAmount($refund->amount, $refund->currency_code) }}</x-p::table.cell>
                        <x-p::table.cell class="text-right">{{ $refund->created_at }}</x-p::table.cell>
                        <x-p::table.cell class="text-right">
                            @if($refund->status->hasAction('delete'))
                                <form action="{{ route(Config::getRouteName('refunds.destroy'), $refund->id) }}" method="POST"
                                      id="delete_refund_{{$refund->id}}">
                                    @csrf @method('DELETE')
                                </form>
                                <x-p::button class="text-red-500" form="delete_refund_{{$refund->id}}" size="small"
                                                color="danger" type="submit">Delete
                                </x-p::button>
                            @endif
                            @if($refund->status->hasAction('retry'))
                                <x-p::buttonlink href="{{route(Config::getRouteName('refunds.retry'), $refund->id)}}">
                                    Retry
                                </x-p::buttonlink>
                            @endif
                        </x-p::table.cell>
                    </x-p::table.row>
                @endforeach
                </tbody>
            </x-p::table>
            <div class="text-gray-500 my-1 mx-2">
                Once refund is sent you cannot revoke it.
            </div>
        @else
            <x-p::not-found message="Refunds not found"/>
        @endif

    </x-p::paper>
@endif
