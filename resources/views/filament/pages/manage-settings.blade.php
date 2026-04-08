<x-filament-panels::page>
    <form id="form" wire:submit.prevent="save">
        {{ $this->form }}
        
        {{-- The button is gone from here! It's now in the header --}}
    </form>
</x-filament-panels::page>