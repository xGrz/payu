@if($payout->status->hasAction('refresh-status') && $payout->updated_at < now()->subHour())
    <form
        action="{{ route('payu.payouts.retry', $payout->id) }}"
        method="POST"
        id="payout_refresh_{{$payout->id}}"
        class="hidden"
    >
        @csrf
        @method('PATCH')
    </form>
    <x-payu::button
        type="submit"
        form="payout_refresh_{{$payout->id}}"
        color="info"
        size="small"
    >
        Refresh
    </x-payu::button>
@endif

@if($payout->status->hasAction('delete'))
    <form
        action="{{route('payu.payouts.destroy', $payout->id)}}"
        method="POST"
        id="payout_delete_{{$payout->id}}"
        class="hidden"
    >
        @csrf
        @method('DELETE')
    </form>
    <x-payu::button
        type="submit"
        color="danger"
        form="payout_delete_{{$payout->id}}"
        size="small"
    >
        Delete
    </x-payu::button>
@endif

@if($payout->status->hasAction('retry'))
    <form
        action="{{route('payu.payouts.retry', $payout->id)}}"
        method="POST"
        id="payout_retry_{{$payout->id}}"
        class="hidden"
    >
        @csrf
        @method('PATCH')
    </form>
    <x-payu::button
        type="submit"
        color="warning"
        form="payout_retry_{{$payout->id}}"
        size="small"
    >
        Retry
    </x-payu::button>
@endif
