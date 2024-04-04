<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <x-payu::paper class="bg-slate-800">
        <x-payu::paper-title title="PayU account balance"/>
        <x-payu::table>
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
        </x-payu::table>
    </x-payu::paper>
    <x-payu::paper class="bg-slate-800">
        @include('payu::payouts.payout-form')
    </x-payu::paper>
</div>

@empty($payouts->toArray())
    <span class="block text-gray-500 mt-2">Payouts not found</span>
@else
    <div class="mt-4"></div>
    <x-payu::pagination.info :source="$payouts"/>
    <x-payu::paper class="bg-slate-800">
        <x-payu::paper-title title="Payout listing"/>

        @if($payouts->count())
            <x-payu::table class="w-full">
                <x-payu::table.thead>
                    <x-payu::table.row>
                        <x-payu::table.header class="text-left">Payout ordered at</x-payu::table.header>
                        <x-payu::table.header class="text-right">Amount</x-payu::table.header>
                        <x-payu::table.header class="text-right">Status</x-payu::table.header>
                        <x-payu::table.header class="text-right"></x-payu::table.header>
                    </x-payu::table.row>
                </x-payu::table.thead>
                <tbody>
                @foreach($payouts as $payout)
                    <x-payu::table.row>
                        <x-payu::table.cell>{{ $payout->created_at }}</x-payu::table.cell>
                        <x-payu::table.cell
                            class="text-right">{{ Number::currency($payout->amount, 'PLN', 'pl') }}</x-payu::table.cell>
                        <x-payu::table.cell class="text-right">
                            <x-payu::status :status="$payout->status"/>
                            @if($payout->errorDescription)
                                <small class="block">{{$payout->errorDescription}}</small>
                            @endif
                        </x-payu::table.cell>
                        <x-payu::table.cell class="text-right text-nowrap">
                            @include('payu::payouts.partials.actions')
                        </x-payu::table.cell>
                    </x-payu::table.row>
                @endforeach
                </tbody>
            </x-payu::table>
            <div class="py-2">
                <x-payu::pagination :source="$payouts"/>
            </div>
        @else
            <x-payu::not-found message="Payouts not found"/>
        @endif
    </x-payu::paper>
@endif


