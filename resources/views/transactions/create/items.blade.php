<div class="grid grid-cols-12 gap-2 p-2">
    @foreach($products as $key => $product)
        <div class="col-span-8">
            <x-payu::input
                label="Name"
                type="text"
                name="items[{{$key}}][name]"
                value="{{ $product['name'] }}"
            />
        </div>
        <div class="col-span-2">
            <x-payu::input
                label="Quantity"
                type="number"
                step="1"
                name="items[{{$key}}][quantity]"
                value="{{ $product['quantity'] }}"
            />
        </div>
        <div class="col-span-2">
            <x-payu::input
                label="Price"
                type="number"
                step="0.01"
                name="items[{{$key}}][price]"
                value="{{ $product['price'] }}"
            />
        </div>
    @endforeach
</div>
{{--<x-payu::table>--}}
{{--    @foreach($products as $key => $product)--}}
{{--        <x-payu::table.row>--}}
{{--            <x-payu::table.cell>--}}
{{--                <x-payu::input--}}
{{--                    type="text"--}}
{{--                    name="item[{{$key}}][name]"--}}
{{--                    value="{{ $products[$key]['name'] }}"--}}
{{--                />--}}
{{--            </x-payu::table.cell>--}}
{{--            <x-payu::table.cell>--}}
{{--                <x-payu::input--}}
{{--                    type="number"--}}
{{--                    step="1"--}}
{{--                    name="item[{{$key}}][quantity]"--}}
{{--                    value="{{ $products[$key]['quantity'] }}"--}}
{{--                />--}}
{{--            </x-payu::table.cell>--}}
{{--            <x-payu::table.cell>--}}
{{--                <x-payu::input--}}
{{--                    type="number"--}}
{{--                    step="0.01"--}}
{{--                    name="item[{{$key}}][price]"--}}
{{--                    value="{{ $products[$key]['price'] }}"--}}
{{--                />--}}
{{--            </x-payu::table.cell>--}}
{{--        </x-payu::table.row>--}}
{{--    @endforeach--}}
{{--</x-payu::table>--}}
{{--</div>--}}
