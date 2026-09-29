<?php

namespace App\Filament\Resources\FooterLinkGroups\Pages;

use App\Filament\Resources\FooterLinkGroups\FooterLinkGroupResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditFooterLinkGroup extends EditRecord
{
    protected static string $resource = FooterLinkGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
