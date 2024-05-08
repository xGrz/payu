@php use \xGrz\PayU\Facades\Config; @endphp
{{--Pay link--}}
@if($transaction->status->hasAction('pay'))
    <x-p-button href="{!! $transaction->link !!}" target="new" size="small">
        Pay
    </x-p-button>
@endif


{{--Delete transction --}}
@if($transaction->status->hasAction('delete'))
    <x-p-button type="submit" form="delete_{{$transaction->id}}" size="small" color="danger">
        Delete
    </x-p-button>
    <form action="{{route(Config::getRouteName('payments.destroy'), $transaction->id)}}" method="POST"
          id="delete_{{$transaction->id}}" class="hidden">
        @csrf @method('DELETE')
    </form>
@endif


{{--Accept transaction--}}
@if($transaction->status->hasAction('accept'))
    <x-p-button type="submit" color="success" size="small" form="accept_{{$transaction->id}}">Accept
    </x-p-button>
    <form action="{{route(Config::getRouteName('payments.accept'), $transaction->id)}}" method="POST"
          id="accept_{{$transaction->id}}" class="hidden">
        @method('PATCH')
        @csrf
    </form>
@endif

{{--Reject transaction--}}
@if($transaction->status->hasAction('reject'))
    <x-p-button type="submit" color="danger" size="small" form="reject_{{$transaction->id}}">Reject</x-p-button>
    <form action="{{route(Config::getRouteName('.payments.reject'), $transaction->id)}}" method="POST"
          id="reject_{{$transaction->id}}" class="hidden">
        @method('DELETE')
        @csrf
    </form>
@endif




