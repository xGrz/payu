@php use \xGrz\PayU\Facades\Config; @endphp
{{--Pay link--}}
@if($transaction->status->hasAction('pay'))
    <x-p-button href="{!! $transaction->link !!}" target="new" size="small">
        Pay
    </x-p-button>
@endif


{{--Delete transction --}}
@if($transaction->status->hasAction('delete'))
    <x-p-button color="danger" size="small" wire:click="deleteTransaction('{{$transaction->id}}')">
        Delete
    </x-p-button>
@endif


{{--Accept transaction--}}
@if($transaction->status->hasAction('accept'))
    <x-p-button color="success" size="small" wire:click="acceptTransaction('{{$transaction->id}}')">
        Accept
    </x-p-button>
@endif

{{--Reject transaction--}}
@if($transaction->status->hasAction('reject'))
    <x-p-button type="submit" color="danger" size="small" wire:click="rejectTransaction('{{$transaction->id}}')">
        Reject
    </x-p-button>
@endif




