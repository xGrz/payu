<div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-7 gap-2 mb-2">

    <x-payu::paper class="bg-indigo-800 text-white col-span-2">
        <h2 class="text-sm leading-tight text-indigo-300">Shop balance</h2>
        <div class="text-xl text-center font-semibold leading-none">{{ $balance->humanAmount->balance }}</div>
    </x-payu::paper>

    <x-payu::paper class="bg-sky-700 text-white col-span-2">
        <h2 class="text-sm leading-tight text-sky-300">Amount reserved for refunds</h2>
        <div class="text-xl text-center font-semibold leading-none">{{ $balance->humanAmount->reserved }}</div>
    </x-payu::paper>

    <x-payu::paper class="bg-sky-500 text-white col-span-2">
        <h2 class="text-sm leading-tight text-sky-200">Available balance</h2>
        <div class="text-xl text-center font-semibold leading-none">{{ $balance->humanAmount->available }}</div>
    </x-payu::paper>

    <x-payu::paper class="bg-gray-200 col-span-2 lg:col-span-1">
        <h2 class="text-sm leading-tight text-gray-500">Options</h2>
        <div class="text-center  leading-none">
            <x-payu::link href="" color="primary">
                Create payout
            </x-payu::link>
        </div>
    </x-payu::paper>

</div>
