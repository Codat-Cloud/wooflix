<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Products\ProductResource;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewProduct extends ViewRecord
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            Action::make('variants')
            ->label('Manage Variants')
            ->icon('heroicon-o-cube')
            ->color('warning')
            ->url(fn ($record) =>
                ProductResource::getUrl('variants', [
                    'record' => $record,
                ])
            ),
        ];
    }
}
