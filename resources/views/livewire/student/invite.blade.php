<div>
    <flux:modal.trigger name="invite-student">
        <flux:button size="sm" variant="primary">Invite Student</flux:button>
    </flux:modal.trigger>

    <flux:modal name="invite-student" class="md:w-96">
        <form wire:submit.prevent="sendInvite" class="space-y-6">
            <div>
                <flux:heading size="lg">Invite Student</flux:heading>
            </div>

            <flux:field>
                <flux:label>Student ID</flux:label>
                <flux:description>An email will be sent to @student.techcampus.org</flux:description>
                <flux:input
                    wire:model="form.studentId"
                    placeholder="1234567"
                    type="text"
                    required
                />
                <flux:error name="form.studentId" />
            </flux:field>

            <div class="flex">
                <flux:spacer />

                <flux:button type="submit" variant="primary">Send Invitation</flux:button>
            </div>
        </form>
    </flux:modal>

</div>
