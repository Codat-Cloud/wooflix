<?php

namespace App\Filament\Resources\FooterLinkGroups\Schemas;

use App\Models\FooterLinkGroup;
use Closure;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class FooterLinkGroupForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Footer Link Details')
                    ->columnSpanFull()
                    ->schema([
                        Grid::make(2)
                            ->schema([

                                // 1. Group Selector (Category Parent)
                                Select::make('parent_id')
                                    ->label('Group Name (Parent)')
                                    ->placeholder('None — This is a Group Header')
                                    // Prevent selecting itself as a parent when editing
                                    ->options(function (?FooterLinkGroup $record) {
                                        return FooterLinkGroup::whereNull('parent_id')
                                            ->when($record, fn($q) => $q->where('id', '!=', $record->id))
                                            ->pluck('name', 'id');
                                    })
                                    ->helperText('Leave empty to create a top-level Group Header (e.g., SHOP FOR). Max 3 groups allowed.')
                                    ->live()
                                    ->searchable()
                                    // Form-level validation: enforces max 3 parent groups
                                    ->rules([
                                        fn(?FooterLinkGroup $record) => function (string $attribute, $value, Closure $fail) use ($record) {
                                            if (blank($value)) {
                                                $query = FooterLinkGroup::whereNull('parent_id');

                                                if ($record && $record->exists) {
                                                    $query->where('id', '!=', $record->id);
                                                }

                                                if ($query->count() >= 3) {
                                                    $fail('Maximum of 3 footer groups allowed. Please choose an existing parent group.');
                                                }
                                            }
                                        },
                                    ]),

                                // 2. Name (Switches dynamically between Link Name and Group Name)
                                TextInput::make('name')
                                    ->label(fn($get): string => $get('parent_id') ? 'Link Name' : 'Group Name')
                                    ->placeholder(fn($get): string => $get('parent_id') ? 'e.g. Dogs, Track Your Order' : 'e.g. SHOP FOR, QUICK LINKS')
                                    ->required()
                                    ->maxLength(100),

                                // 3. Destination URL (Only required when it belongs to a parent group)
                                TextInput::make('url')
                                    ->label('Destination URL')
                                    ->placeholder('e.g. https://...')
                                    ->visible(fn($get): bool => filled($get('parent_id')))
                                    ->required(fn($get): bool => filled($get('parent_id')))
                                    ->maxLength(255)
                                    ->columnSpanFull(),

                                // 4. Sort Order
                                TextInput::make('sort_order')
                                    ->numeric()
                                    ->default(0)
                                    ->required(),

                                // 5. Active Status
                                Toggle::make('is_active')
                                    ->label('Is Active')
                                    ->default(true)
                                    ->required()
                                    ->inline(false),
                            ]),
                    ]),
            ]);
    }
}
