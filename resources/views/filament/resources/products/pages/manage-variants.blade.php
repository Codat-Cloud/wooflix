<x-filament::page>
    {{-- Header --}}
    <header class="flex items-center justify-between gap-3 mb-6">
        <div>
            <h2 class="text-2xl font-bold tracking-tight text-gray-950 dark:text-white">
                Manage Variants
            </h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Configure pricing and inventory for <span class="font-medium text-primary-600">{{ $record->name }}</span>
            </p>
        </div>
    </header>

    {{-- Top Form (Create Variants) --}}
    <div class="mb-10">
        {{ $this->form }}
    </div>

</x-filament::page>