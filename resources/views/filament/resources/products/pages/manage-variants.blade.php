<x-filament::page>
    <div class="mb-10">
        {{ $this->form }}
    </div>

</x-filament::page>

{{-- <x-filament-panels::page>

    <div class="mb-4">
        <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100">
            {{ $record->name }}
        </h2>
        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
            {{ $record->is_active ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
            {{ $record->is_active ? 'Published' : 'Draft' }}
        </span>
    </div>

    <x-filament-panels::form wire:submit="saveVariants">
        {{ $this->form }}
    </x-filament-panels::form>

</x-filament-panels::page> --}}