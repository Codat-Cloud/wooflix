<?php

namespace App\Filament\Resources\HomeSections\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class HomeSectionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            // ->reorderable('sort_order') // 🔥 enables drag & drop
            ->columns([
                TextColumn::make('title')
                    ->searchable(),

                TextColumn::make('type'),

                TextColumn::make('layout'),

                TextColumn::make('sort_order')
                    ->sortable(),

                ToggleColumn::make('is_active'),

            ])
            ->defaultSort('sort_order')
            ->filters([
                //
            ])
            ->recordActions([
                // ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
