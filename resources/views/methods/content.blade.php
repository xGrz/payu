@php use \xGrz\PayU\Facades\Config; @endphp
<x-p::paper class="bg-slate-800">
    <x-p::paper-title title="Payment methods">
        <x-p::buttonlink href="{{ route(Config::getRouteName('methods.synchronize')) }}">
            Synchronize now
        </x-p::buttonlink>
    </x-p::paper-title>
    @if($methods->count())
        <x-p::table>
            <x-p::table.th class="text-center">Available<br/>in PayU</x-p::table.th>
            <x-p::table.th class="text-left">Name</x-p::table.th>
            <x-p::table.th class="text-center">Image</x-p::table.th>
            <x-p::table.th class="text-center">Symbol</x-p::table.th>
            <x-p::table.th class="text-center">Used</x-p::table.th>
            <x-p::table.th class="text-center">Active</x-p::table.th>
            <x-p::table.body>
            @foreach($methods as $method)
                <x-p::table.row>
                    <x-p::table.cell class="text-center">
                        @if($method->available)
                            <x-p::icons.available class="inline w-8 h-8 text-green-500"/>
                        @else
                            <x-p::icons.notavailable class="inline w-8 h-8 text-red-600"/>
                        @endif
                    </x-p::table.cell>
                    <x-p::table.cell class="text-left">
                        {{ $method->name }}
                        <small class="block">
                            <span class="text-slate-600">{{ $method->min }}</span> - <span
                                class="text-slate-600">{{ $method->max }}</span>
                        </small>
                    </x-p::table.cell>
                    <x-p::table.cell class="text-center m-auto">
                        <img class="w-16 inline bg-white p-1" src="{{ $method->image }}" alt="{{$method->name}}"/>
                    </x-p::table.cell>
                    <x-p::table.cell class="text-center">
                        {{ $method->code }}
                    </x-p::table.cell>
                    <x-p::table.cell class="text-center">
                            <span @class([
                                'text-green-700' => $method->transactions_count,
                                'text-slate-600' => !$method->transactions_count
                                ])>{{ $method->transactions_count }}</span>
                    </x-p::table.cell>
                    <x-p::table.cell class="text-center">
                        @if($method->available)
                            @if($method->active)
                                <form action="{{ route(Config::getRouteName('methods.deactivate'), $method) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <x-p::button type="submit" size="small" color="success">ACTIVE
                                    </x-p::button>
                                </form>
                            @else
                                <form action="{{ route(Config::getRouteName('methods.activate'), $method) }}" method="POST">
                                    @csrf
                                    <x-p::button type="submit" size="small" color="danger">DISABLED
                                    </x-p::button>
                                </form>
                            @endif
                        @endif
                    </x-p::table.cell>
                </x-p::table.row>
            @endforeach
            </x-p::table.body>
        </x-p::table>
    @else
        <x-p::not-found message="Please synchronize payment methods"/>
    @endif
</x-p::paper>
