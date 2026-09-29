<?php

namespace App\Filament\Resources\FooterLinkGroups\Pages;

use App\Filament\Resources\FooterLinkGroups\FooterLinkGroupResource;
use App\Models\FooterLinkGroup;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Validation\ValidationException;

class ListFooterLinkGroups extends ListRecords
{
    protected static string $resource = FooterLinkGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->mutateFormDataBeforeCreate(function (array $data): array {
                    // Check if attempting to create a 4th top-level group
                    if (empty($data['parent_id'])) {
                        $currentGroupCount = FooterLinkGroup::whereNull('parent_id')->count();
                        if ($currentGroupCount >= 3) {
                            Notification::make()
                                ->title('Maximum 3 Groups Allowed')
                                ->body('You already have 3 parent groups. Please select an existing group to add links under it.')
                                ->danger()
                                ->send();

                            throw ValidationException::withMessages([
                                'parent_id' => 'Maximum of 3 groups allowed in the footer.',
                            ]);
                        }
                    }

                    return $data;
                }),
        ];
    }
}
