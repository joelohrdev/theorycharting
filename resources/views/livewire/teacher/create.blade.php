<div>
    <flux:modal.trigger name="create-teacher">
        <flux:button size="sm" variant="primary">Add Teacher</flux:button>
    </flux:modal.trigger>

    <flux:modal name="create-teacher" class="md:w-96">
        <form wire:submit.prevent="create" class="space-y-6">
            <div>
                <flux:heading size="lg">Add Teacher</flux:heading>
                <flux:text class="mt-2">The teacher will receive an email to update password the in order to complete registration.</flux:text>
            </div>

            <flux:input wire:model="name" label="Name" placeholder="Teachers name" />

            <flux:input wire:model="email" label="Email Address" placeholder="example@email.com" type="email" />

            <div class="flex">
                <flux:spacer />

                <flux:button type="submit" variant="primary">Create</flux:button>
            </div>
        </form>
    </flux:modal>
</div>
