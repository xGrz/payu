<div class="container px-1 mx-auto">
    <div class="flex flex-col md:flex-row my-1 px-1 content-center">
        <h1 class="text-xl md:text-3xl text-slate-200 py-1 md:py-0 font-semibold md:self-center grow">xGrz/PayU | {{ $title ?? 'Page title' }}</h1>
        <nav class="flex items-center mt-1 md:mt-0">
            <x-payu::nav-item routeName="payu.payments.index" label="Transactions">
                <x-payu::icons.transactions class="w-5 md:w-8 h-5 md:h-8 mr-1 md:block"/>
            </x-payu::nav-item>
            <x-payu::nav-item routeName="payu.refunds.index" label="Refunds">
                <x-payu::icons.refund class="w-5 md:w-8 h-5 md:h-8 mr-1 md:block" />
            </x-payu::nav-item>
            <x-payu::nav-item routeName="payu.payouts.index" label="Payouts">
                <x-payu::icons.payout class="w-5 md:w-8 h-5 md:h-8 mr-1 md:block" />
            </x-payu::nav-item>
        </nav>
    </div>
    <hr class="border-slate-600"/>
    <div class="mb-4 text-white">
        @yield('breadcrumbs')
    </div>
</div>
