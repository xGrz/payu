<x-p::modal title="Create refund" :watchErrors="['amount', 'description', 'bankDescription']">
    <x-slot:trigger>
        <x-p-button @click="modelOpen =!modelOpen">
            Create refund
        </x-p-button>
    </x-slot:trigger>
    <x-slot:modalContent>
        <form action="{{route('payu.refunds.store', $transaction->id)}}" method="POST" class="px-2">
            @csrf
            <x-p-input
                    type="float"
                    name="amount"
                    max="{{$transaction->maxRefundAmount()}}"
                    value="{{$transaction->maxRefundAmount()}}"
                    label="Amount"
            />
            <x-p-input
                label="Description (reason)"
                name="description"
            />

            <x-p-input
                label="Bank description"
                name="bankDescription"
            />

            <div class="flex justify-end mt-6">
                <x-p-button color="success" type="submit">
                    Send refund request
                </x-p-button>
            </div>
        </form>
    </x-slot:modalContent>
</x-p::modal>
