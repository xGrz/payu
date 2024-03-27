<div>
    <x-payu::paper-title title="Create new payout"/>

    <form method="POST" action="{{ route('payu.payouts.store') }}">
        @csrf

        <label class="block px-2 font-bold mb-2 mt-[.05rem]">
            <small class="text-slate-400 block">Amount</small>
            <div class="flex w-100 mt-1">
                <input
                    @if ($balance->amount->balance == $balance->amount->available) disabled @endif type="text"
                    name="payoutAmount" value="{{ old("payoutAmount", $balance->amount->available) }}"
                    class="w-full inline-block grow shrink border bg-slate-300 border-gray-300 focus:bg-gray-200 text-slate-600 focus:text-slate-700 rounded-l-md focus:outline-none text-right py-2 px-4 disabled:bg-gray-100 disabled:text-gray-400"
                />
                <button class="grow-0 shrink-0 p-2 bg-green-700 hover:bg-green-800 text-white rounded-r-lg">Payout</button>
            </div>
            @error('payoutAmount')
            <div class="h-6 text-red-500">
                <small>{{ $message }}</small>
            </div>
            @enderror
        </label>

    </form>
</div>
