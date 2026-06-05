<?php

namespace App\Filament\Resources\AbandonedCarts\Pages;

use App\Filament\Resources\AbandonedCarts\AbandonedCartResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageAbandonedCarts extends ManageRecords
{
    protected static string $resource = AbandonedCartResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
