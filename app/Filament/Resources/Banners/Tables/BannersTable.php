<?php

namespace App\Filament\Resources\Banners\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class BannersTable
{
    public static function configure(Table $table): Table
    {
        return $table
        ->reorderable('sort_order')
            ->columns([
                ImageColumn::make('desktop_image')
                ->disk('public')
                ->circular()
                ->label('Desktop Banner'),
                
                ImageColumn::make('mobile_image')
                ->disk('public')
                ->circular()
                ->label('Mobile Banner'),

                TextColumn::make('title')
                    ->limit(30)
                    ->searchable(),

                TextColumn::make('sort_order')
                    ->sortable(),

                ToggleColumn::make('is_active'),
            ])
            ->defaultSort('sort_order')
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
