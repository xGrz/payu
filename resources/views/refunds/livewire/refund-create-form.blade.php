<div>
    <x-p-paper>
        <x-slot:title>Create refund</x-slot:title>
        <x-slot:actions>
            <button type="button" wire:click="$dispatch('closeModal')"
                    class="text-red-600 hover:bg-red-600 hover:text-white p-1 rounded-full">
                <x-p::icons.close/>
            </button>
        </x-slot:actions>
        <form wire:submit.prevent="store">
            <x-p-input type="float" wire:model.live="amount" class="text-right" label="Refund amount"/>
            <x-p-input wire:model="description" label="Reason"/>
            <x-p-input wire:model="bank_description" label="Bank description"/>
            <div class="pt-4 text-right">
                <x-p-button type="submit">Dispatch refund</x-p-button>
            </div>
        </form>
    </x-p-paper>
</div>
