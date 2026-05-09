<?php

namespace App\Filament\Resources\ProductFilterTags\Pages;

use App\Filament\Resources\ProductFilterTags\ProductFilterTagResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListProductFilterTags extends ListRecords
{
    protected static string $resource = ProductFilterTagResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
