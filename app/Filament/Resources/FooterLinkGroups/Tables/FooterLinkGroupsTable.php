<?php

namespace App\Filament\Resources\FooterLinkGroups\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class FooterLinkGroupsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->defaultGroup('parent.name') // 🟢 Groups the table visually by parent group name
            ->columns([
                TextColumn::make('name')
                    ->label('Name')
                    ->weight(fn($record) => $record->parent_id === null ? 'bold' : 'normal')
                    ->searchable(),

                TextColumn::make('url')
                    ->label('Destination URL')
                    ->placeholder('— (Group Header)')
                    ->color('gray')
                    ->limit(35),

                TextColumn::make('sort_order')
                    ->label('Order')
                    ->sortable(),

                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
