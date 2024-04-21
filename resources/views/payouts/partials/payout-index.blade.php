<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <x-p::paper class="bg-slate-800">
        <x-p::paper-title title="PayU account balance"/>
        <x-p::table>
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
        </x-p::table>
    </x-p::paper>
    <x-p::paper class="bg-slate-800">
        @include('payu::payouts.payout-form')
    </x-p::paper>
</div>

@empty($payouts->toArray())
    <span class="block text-gray-500 mt-2">Payouts not found</span>
@else
    <div class="mt-4"></div>
    <x-p::pagination.info :source="$payouts"/>
    <x-p::paper class="bg-slate-800">
        <x-p::paper-title title="Payout listing"/>

        @if($payouts->count())
            <x-p::table class="w-full">
                <x-p::table.head>
                    <x-p::table.row>
                        <x-p::table.th class="text-left">Payout ordered at</x-p::table.th>
                        <x-p::table.th class="text-right">Amount</x-p::table.th>
                        <x-p::table.th class="text-right">Status</x-p::table.th>
                        <x-p::table.th class="text-right"></x-p::table.th>
                    </x-p::table.row>
                </x-p::table.head>
                <tbody>
                @foreach($payouts as $payout)
                    <x-p::table.row>
                        <x-p::table.cell>{{ $payout->created_at }}</x-p::table.cell>
                        <x-p::table.cell
                            class="text-right">{{ Number::currency($payout->amount, 'PLN', 'pl') }}</x-p::table.cell>
                        <x-p::table.cell class="text-right">
                            <x-p::status :status="$payout->status"/>
                            @if($payout->errorDescription)
                                <small class="block">{{$payout->errorDescription}}</small>
                            @endif
                        </x-p::table.cell>
                        <x-p::table.cell class="text-right text-nowrap">
                            @include('payu::payouts.partials.actions')
                        </x-p::table.cell>
                    </x-p::table.row>
                @endforeach
                </tbody>
            </x-p::table>
            <div class="py-2">
                <x-p::pagination :source="$payouts"/>
            </div>
        @else
            <x-p::not-found message="Payouts not found"/>
        @endif
    </x-p::paper>
@endif


