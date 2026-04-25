<?php

namespace App\Filament\Resources\Wholesales\Pages;

use App\Filament\Resources\Wholesales\WholesaleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListWholesales extends ListRecords
{
    protected static string $resource = WholesaleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
