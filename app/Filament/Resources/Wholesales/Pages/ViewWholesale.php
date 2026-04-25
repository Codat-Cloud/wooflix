<?php

namespace App\Filament\Resources\Wholesales\Pages;

use App\Filament\Resources\Wholesales\WholesaleResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewWholesale extends ViewRecord
{
    protected static string $resource = WholesaleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
