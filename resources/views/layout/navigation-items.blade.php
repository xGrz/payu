@php use \xGrz\PayU\Facades\Config; @endphp
<x-p-nav-item routeName="{{ Config::getRouteName('payments.index') }}">
    <x-p::icons.transactions class="w-5 md:w-8 h-5 md:h-8 mr-1 md:block"/>
    Transactions
</x-p-nav-item>
<x-p-nav-item routeName="{{ Config::getRouteName('refunds.index') }}" >
    <x-p::icons.refund class="w-5 md:w-8 h-5 md:h-8 mr-1 md:block" />
    Refunds
</x-p-nav-item>
<x-p-nav-item routeName="{{ Config::getRouteName('payouts.index') }}">
    <x-p::icons.payout class="w-5 md:w-8 h-5 md:h-8 mr-1 md:block" />
    Payouts
</x-p-nav-item>
<x-p-nav-item routeName="{{ Config::getRouteName('methods.index') }}" >
    <x-p::icons.methods class="w-5 md:w-8 h-5 md:h-8 mr-1 md:block" />
    Methods
</x-p-nav-item>
