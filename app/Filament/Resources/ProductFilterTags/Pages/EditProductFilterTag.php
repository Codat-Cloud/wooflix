<?php

namespace App\Filament\Resources\ProductFilterTags\Pages;

use App\Filament\Resources\ProductFilterTags\ProductFilterTagResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditProductFilterTag extends EditRecord
{
    protected static string $resource = ProductFilterTagResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
