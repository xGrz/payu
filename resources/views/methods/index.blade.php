@extends('payu::app')


@section('content')
    <x-payu::paper class="bg-slate-800">
        <x-payu::paper-title title="Payment methods">
            <x-payu::buttonlink href="{{ route('payu.methods.synchronize') }}">
                Synchronize now
            </x-payu::buttonlink>
        </x-payu::paper-title>
        @if($methods->count())
            <x-payu::table>
                <x-payu::table.header class="text-center">Active</x-payu::table.header>
                <x-payu::table.header class="text-left">Name</x-payu::table.header>
                <x-payu::table.header class="text-center">Image</x-payu::table.header>
                <x-payu::table.header class="text-center">Symbol</x-payu::table.header>
                <tbody>
                @foreach($methods as $method)
                    <x-payu::table.row>
                        <x-payu::table.cell class="text-center">
                            @if($method->available)
                                <x-payu::icons.available class="inline w-8 h-8 text-green-500"/>
                            @else
                                <x-payu::icons.notavailable class="inline w-8 h-8 text-red-600"/>
                            @endif
                        </x-payu::table.cell>
                        <x-payu::table.cell class="text-left">
                            {{ $method->name }}
                            <small class="block">
                                <span class="text-slate-600">{{ $method->min }}</span> - <span
                                    class="text-slate-600">{{ $method->max }}</span>
                            </small>
                        </x-payu::table.cell>
                        <x-payu::table.cell class="text-center m-auto">
                            <img class="w-16 inline bg-white p-1" src="{{ $method->image }}" alt="{{$method->name}}"/>
                        </x-payu::table.cell>
                        <x-payu::table.cell class="text-center">
                            {{ $method->code }}
                        </x-payu::table.cell>
                    </x-payu::table.row>
                @endforeach
                </tbody>
            </x-payu::table>
        @else
            <x-payu::not-found message="Please synchronize payment methods"/>
        @endif
    </x-payu::paper>

@endsection
