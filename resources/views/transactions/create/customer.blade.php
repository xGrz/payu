<div class="grid grid-cols-12 gap-2 p-2">
    <div class="col-span-12">
        <x-p-input
            label="Customer name"
            name="customer[name]"
            value="{{$customer['name']}}"
        />
    </div>
    <div class="col-span-8">
        <x-p-input
            label="Street"
            name="customer[street]"
            value="{{$customer['street']}}"
        />
    </div>
    <div class="col-span-2">
        <x-p-input
            label="House number"
            name="customer[house_number]"
            value="{{$customer['house_number']}}"
        />
    </div>
    <div class="col-span-2">
        <x-p-input
            label="Apartment number"
            name="customer[apartment_number]"
            value="{{$customer['apartment_number']}}"
        />
    </div>
    <div class="col-span-6">
        <x-p-input
            label="City"
            name="customer[city]"
            value="{{$customer['city']}}"
        />
    </div>
    <div class="col-span-6">
        <x-p-input
            label="Postal code"
            name="customer[postalCode]"
            value="{{$customer['postalCode']}}"
        />
    </div>
    <div class="col-span-6">
        <x-p-input
            label="E-mail"
            name="customer[email]"
            value="{{$customer['email']}}"
        />
    </div>
    <div class="col-span-6">
        <x-p-input
            label="Phone"
            name="customer[phone]"
            value="{{$customer['phone']}}"
        />
    </div>
</div>
