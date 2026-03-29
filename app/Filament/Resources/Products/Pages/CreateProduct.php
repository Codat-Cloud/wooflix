<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Products\ProductResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

    protected function getFormActions(): array
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

    // ✅ Default Save → Publish
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['is_active'] = true;
        return $data;
    }

    public function saveAndGoToVariants()
    {
        $this->form->validate();

        $data = $this->form->getState();

        $variantInputs = $data['variant_inputs'] ?? [];

        unset($data['variant_inputs']);

        $data['is_active'] = false;

        $record = $this->handleRecordCreation($data);

        // 🔥 SAVE VARIANT STRUCTURE TEMPORARILY
        session([
            'variant_inputs_' . $record->id => $variantInputs
        ]);

        return $this->redirect(
            ProductResource::getUrl('variants', [
                'record' => $record,
            ])
        );
    }

    // ✅ Save & Go to Variants → Draft
    // public function saveAndGoToVariants()
    // {
    //     // ✅ Validate (important)
    //     $this->form->validate();

    //     // ✅ Let Filament prepare data properly
    //     $data = $this->form->getState(); // instead of mutate first
    //     $data = $this->mutateFormDataBeforeCreate($data);

    //     // draft mode
    //     $data['is_active'] = false;

    //     // ✅ THIS ensures uploads + relationships are handled
    //     $record = $this->handleRecordCreation($data);

    //     // ✅ VERY IMPORTANT → ensure relations are saved
    //     $this->form->model($record)->saveRelationships();

    //     $values = explode(',', $this->form->getState()['values'] ?? '');

    //     foreach ($values as $value) {
    //         $record->variants()->create([
    //             'name' => trim($value),
    //             'price' => 0,
    //             'stock' => 0,
    //             'is_active' => true,
    //         ]);
    //     }

    //     // ✅ Redirect safely
    //     return $this->redirect(
    //         ProductResource::getUrl('variants', [
    //             'record' => $record,
    //         ])
    //     );
    // }
}
