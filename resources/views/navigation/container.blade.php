<div class="container px-1 mx-auto">
    <div class="flex">
        <h1 class="text-3xl pt-4 text-slate-300 font-semibold grow">xGrz/PayU | {{ $title ?? 'Page title' }}</h1>
        <nav class="flex">
            <x-payu::nav-item routeName="payu.payments.index" label="Transactions">
                <x-payu::icons.transactions class="w-8 h-8"/>
            </x-payu::nav-item>
            <x-payu::nav-item routeName="payu.refunds.index" label="Refunds">
                <x-payu::icons.refund class="w-8 h-8" />
            </x-payu::nav-item>
            <x-payu::nav-item routeName="payu.payouts.index" label="Payouts">
                <x-payu::icons.payout class="w-8 h-8" />
            </x-payu::nav-item>
        </nav>
    </div>
    <hr class="border-slate-600"/>
    <div class="mb-4 text-white">
        @yield('breadcrumbs')
    </div>
</div>
