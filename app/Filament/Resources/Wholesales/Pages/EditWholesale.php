<?php

namespace App\Filament\Resources\Wholesales\Pages;

use App\Filament\Resources\Wholesales\WholesaleResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditWholesale extends EditRecord
{
    protected static string $resource = WholesaleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
