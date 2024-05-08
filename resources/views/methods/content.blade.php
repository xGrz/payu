@php use \xGrz\PayU\Facades\Config; @endphp
<x-p-paper class="bg-slate-800">
    <x-slot:title>Payment methods</x-slot:title>
    <x-slot:actions>
        <x-p-button href="{{ route(Config::getRouteName('methods.synchronize')) }}">
            Synchronize now
        </x-p-button>
    </x-slot:actions>
    @if($methods->count())
        <x-p-table>
            <x-p-thead>
                <x-p-th class="text-center">Available<br/>in PayU</x-p-th>
                <x-p-th class="text-left">Name</x-p-th>
                <x-p-th class="text-center">Image</x-p-th>
                <x-p-th class="text-center">Symbol</x-p-th>
                <x-p-th class="text-center">Used</x-p-th>
                <x-p-th class="text-center">Active</x-p-th>
            </x-p-thead>
            <x-p-tbody>
                @foreach($methods as $method)
                    <x-p-tr>
                        <x-p-td class="text-center">
                            @if($method->available)
                                <x-p::icons.available class="inline w-8 h-8 text-green-500"/>
                            @else
                                <x-p::icons.notavailable class="inline w-8 h-8 text-red-600"/>
                            @endif
                        </x-p-td>
                        <x-p-td class="text-left">
                            {{ $method->name }}
                            <small class="block">
                                <span class="text-slate-600">{{ $method->min }}</span> - <span
                                    class="text-slate-600">{{ $method->max }}</span>
                            </small>
                        </x-p-td>
                        <x-p-td class="text-center m-auto">
                            <img class="w-16 inline bg-white p-1" src="{{ $method->image }}" alt="{{$method->name}}"/>
                        </x-p-td>
                        <x-p-td class="text-center">
                            {{ $method->code }}
                        </x-p-td>
                        <x-p-td class="text-center">
                            <span @class([
                                'text-green-700' => $method->transactions_count,
                                'text-slate-600' => !$method->transactions_count
                                ])>{{ $method->transactions_count }}</span>
                        </x-p-td>
                        <x-p-td class="text-center">
                            @if($method->available)
                                @if($method->active)
                                    <form action="{{ route(Config::getRouteName('methods.deactivate'), $method) }}"
                                          method="POST">
                                        @csrf @method('DELETE')
                                        <x-p-button type="submit" size="small" color="success">ACTIVE
                                        </x-p-button>
                                    </form>
                                @else
                                    <form action="{{ route(Config::getRouteName('methods.activate'), $method) }}"
                                          method="POST">
                                        @csrf
                                        <x-p-button type="submit" size="small" color="danger">DISABLED
                                        </x-p-button>
                                    </form>
                                @endif
                            @endif
                        </x-p-td>
                    </x-p-tr>
                @endforeach
            </x-p-tbody>
        </x-p-table>
    @else
        <x-p-not-found message="Please synchronize payment methods"/>
    @endif
</x-p-paper>
