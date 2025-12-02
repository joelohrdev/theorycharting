<x-layouts.app :title="$student->name">
    <div class="mb-5">
        <flux:heading size="xl">{{ $student->name }}</flux:heading>
        <flux:text>{{ $student->email }}</flux:text>
    </div>
    <flux:separator class="mb-4" />
    <livewire:student.show :student="$student" />
</x-layouts.app>
