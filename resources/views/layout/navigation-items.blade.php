@php use \xGrz\PayU\Facades\Config; @endphp
<x-p::nav-item routeName="{{ Config::getRouteName('payments.index') }}" label="Transactions">
    <x-p::icons.transactions class="w-5 md:w-8 h-5 md:h-8 mr-1 md:block"/>
</x-p::nav-item>
<x-p::nav-item routeName="{{ Config::getRouteName('refunds.index') }}" label="Refunds">
    <x-p::icons.refund class="w-5 md:w-8 h-5 md:h-8 mr-1 md:block" />
</x-p::nav-item>
<x-p::nav-item routeName="{{ Config::getRouteName('payouts.index') }}" label="Payouts">
    <x-p::icons.payout class="w-5 md:w-8 h-5 md:h-8 mr-1 md:block" />
</x-p::nav-item>
<x-p::nav-item routeName="{{ Config::getRouteName('methods.index') }}" label="Methods">
    <x-p::icons.methods class="w-5 md:w-8 h-5 md:h-8 mr-1 md:block" />
</x-p::nav-item>
