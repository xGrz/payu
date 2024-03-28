{{--Pay link--}}
@if($transaction->status->actionAvailable('pay'))
    <x-payu::buttonlink href="{!! $transaction->link !!}" target="new" size="small">
        Pay
    </x-payu::buttonlink>
@endif


{{--Delete transction --}}
@if($transaction->status->actionAvailable('delete'))
    <x-payu::button type="submit" form="delete_{{$transaction->id}}" size="small" color="danger">
        Delete
    </x-payu::button>
    <form action="{{route('payu.payments.destroy', $transaction->id)}}" method="POST" id="delete_{{$transaction->id}}" class="hidden">
        @csrf @method('DELETE')
    </form>
@endif


{{--Accept transaction--}}
@if($transaction->status->actionAvailable('accept'))
    <x-payu::button type="submit" color="success" size="small" form="accept_{{$transaction->id}}">Accept</x-payu::button>
    <form action="{{route('payu.payments.accept', $transaction->id)}}" method="POST" id="accept_{{$transaction->id}}" class="hidden">
        @method('PATCH')
        @csrf
    </form>
@endif

{{--Reject transaction--}}
@if($transaction->status->actionAvailable('reject'))
    <x-payu::button type="submit" color="danger" size="small" form="reject_{{$transaction->id}}">Reject</x-payu::button>
    <form action="{{route('payu.payments.reject', $transaction->id)}}" method="POST" id="reject_{{$transaction->id}}" class="hidden">
        @method('DELETE')
        @csrf
    </form>
@endif




