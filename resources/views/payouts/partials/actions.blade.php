@php use \xGrz\PayU\Facades\Config; @endphp
@if($payout->status->hasAction('refresh-status') && $payout->updated_at < now()->subHour())
    <form
        action="{{ route(Config::getRouteName('payouts.retry'), $payout->id) }}"
        method="POST"
        id="payout_refresh_{{$payout->id}}"
        class="hidden"
    >
        @csrf
        @method('PATCH')
    </form>
    <x-p-button
        type="submit"
        form="payout_refresh_{{$payout->id}}"
        color="info"
        size="small"
    >
        Refresh
    </x-p-button>
@endif

@if($payout->status->hasAction('delete'))
    <form
        action="{{route(Config::getRouteName('payouts.destroy'), $payout->id)}}"
        method="POST"
        id="payout_delete_{{$payout->id}}"
        class="hidden"
    >
        @csrf
        @method('DELETE')
    </form>
    <x-p-button
        type="submit"
        color="danger"
        form="payout_delete_{{$payout->id}}"
        size="small"
    >
        Delete
    </x-p-button>
@endif

@if($payout->status->hasAction('retry'))
    <form
        action="{{route(Config::getRouteName('payouts.retry'), $payout->id)}}"
        method="POST"
        id="payout_retry_{{$payout->id}}"
        class="hidden"
    >
        @csrf
        @method('PATCH')
    </form>
    <x-p-button
        type="submit"
        color="warning"
        form="payout_retry_{{$payout->id}}"
        size="small"
    >
        Retry
    </x-p-button>
@endif
