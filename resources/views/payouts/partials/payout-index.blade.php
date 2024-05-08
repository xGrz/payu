<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <x-p-paper>
        <x-slot:title>PayU account balance</x-slot:title>
        <x-p-table>
            <tbody>
            <tr>
                <td>Balance</td>
                <td class="text-right">{{ $balance->humanAmount->balance}}</td>
            </tr>
            <tr>
                <td>Reserved</td>
                <td class="text-right">{{ $balance->humanAmount->reserved}}</td>
            </tr>
            <tr>
                <td>Available</td>
                <td class="text-right">{{ $balance->humanAmount->available}}</td>
            </tr>
            </tbody>
        </x-p-table>
    </x-p-paper>
    @include('payu::payouts.payout-form')
</div>

@empty($payouts->toArray())
    <span class="block text-gray-500 mt-2">Payouts not found</span>
@else
    <div class="mt-4"></div>
    <x-p-pagination info-only :source="$payouts"/>
    <x-p-paper class="bg-slate-800">
        <x-slot:title>Payout listing</x-slot:title>

        @if($payouts->count())
            <x-p-table class="w-full">
                <x-p-thead>
                    <x-p-tr>
                        <x-p-th class="text-left">Payout ordered at</x-p-th>
                        <x-p-th class="text-right">Amount</x-p-th>
                        <x-p-th class="text-right">Status</x-p-th>
                        <x-p-th class="text-right"></x-p-th>
                    </x-p-tr>
                </x-p-thead>
                <x-p-tbody>
                @foreach($payouts as $payout)
                    <x-p-tr>
                        <x-p-td>{{ $payout->created_at }}</x-p-td>
                        <x-p-td
                            class="text-right">{{ Number::currency($payout->amount, 'PLN', 'pl') }}</x-p-td>
                        <x-p-td class="text-right">
                            <x-p-status :status="$payout->status"/>
                            @if($payout->errorDescription)
                                <small class="block">{{$payout->errorDescription}}</small>
                            @endif
                        </x-p-td>
                        <x-p-td class="text-right text-nowrap">
                            @include('payu::payouts.partials.actions')
                        </x-p-td>
                    </x-p-tr>
                @endforeach
                </x-p-tbody>
            </x-p-table>
            <div class="py-2">
                <x-p-pagination :source="$payouts"/>
            </div>
        @else
            <x-p-not-found message="Payouts not found"/>
        @endif
    </x-p-paper>
@endif


