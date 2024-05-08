<div class="grid grid-cols-12 gap-2 p-2">
    @foreach($products as $key => $product)
        <div class="col-span-8">
            <x-p-input
                label="Name"
                type="text"
                name="items[{{$key}}][name]"
                value="{{ $product['name'] }}"
            />
        </div>
        <div class="col-span-2">
            <x-p-input
                label="Quantity"
                type="integer"
                name="items[{{$key}}][quantity]"
                value="{{ $product['quantity'] }}"
            />
        </div>
        <div class="col-span-2">
            <x-p-input
                label="Price"
                type="float"
                name="items[{{$key}}][price]"
                value="{{ $product['price'] }}"
                class="text-right"
            />
        </div>
    @endforeach
</div>
