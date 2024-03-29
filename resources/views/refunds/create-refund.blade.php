<x-payu::modal title="Create refund" :watchErrors="['amount', 'description', 'bankDescription']">
    <x-slot:trigger>
        <x-payu::button @click="modelOpen =!modelOpen">
            Create refund
        </x-payu::button>
    </x-slot:trigger>
    <x-slot:modalContent>
        <form action="{{route('payu.refunds.store', $transaction->id)}}" method="POST" class="px-2">
            @csrf
            <x-payu::input
                type="number"
                name="amount"
                step="0.01"
                max="{{ ($transaction->amount - $transaction->refunded) / 100}}"
                value="{{($transaction->amount - $transaction->refunded) / 100 }}"
                label="Amount"
            />
            <x-payu::input
                label="Description (reason)"
                name="description"
            />

            <x-payu::input
                label="Bank description"
                name="bankDescription"
            />

            <div class="flex justify-end mt-6">
                <x-payu::button color="success" type="submit">
                    Send refund request
                </x-payu::button>
            </div>
        </form>
    </x-slot:modalContent>
</x-payu::modal>
