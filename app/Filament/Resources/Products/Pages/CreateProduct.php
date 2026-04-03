<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Products\ProductResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('save')
                ->label('Save & Publish')
                ->action('create')
                ->color('success'),

            Action::make('save_and_variants')
                ->label('Save & Add Variants')
                ->action('saveAndGoToVariants')
                ->color('primary'),
        ];
    }

    protected function getFormActions(): array
    {
        return [

            // Action::make('save')
            //     ->label('Save & Publish')
            //     ->action('create')
            //     ->color('success'),

            // Action::make('save_and_variants')
            //     ->label('Save & Add Variants')
            //     ->action('saveAndGoToVariants')
            //     ->color('primary'),

        ];
    }

    // ✅ Default Save → Publish
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['is_active'] = true;
        return $data;
    }

    public function saveAndGoToVariants()
    {
        $this->form->validate();

        $data = $this->mutateFormDataBeforeCreate(
            $this->form->getState()
        );

        $data['is_active'] = false;

        $record = $this->handleRecordCreation($data);

        // 🔥 IMPORTANT FIX
        $this->record = $record;

        $this->form->model($record)->saveRelationships();

        return redirect()->route(
            'filament.admin.resources.products.variants',
            ['record' => $record]
        );
    }
}
