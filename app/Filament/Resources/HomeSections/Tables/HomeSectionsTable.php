<?php

namespace App\Filament\Resources\HomeSections\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
// use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class HomeSectionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->reorderable('sort_order')
            ->columns([
                TextColumn::make('title')
                    ->searchable(),

                TextColumn::make('type')
                    ->badge() // makes it a badge
                    ->formatStateUsing(function ($state) {
                        return match ($state) {
                            'brand' => 'Brand',
                            'product' => 'Products',
                            'categroy' => 'Catgeories',
                            default => ucfirst(str_replace('_', ' ', $state)),
                        };
                    })
                    ->color(function ($state) {
                        return match ($state) {
                            'brand' => 'info',
                            'product' => 'success',
                            'category' => 'light',
                            'scroll' => 'gray',
                            default => 'gray',
                        };
                    }),

                TextColumn::make('layout')
                    ->badge() // makes it a badge
                    ->formatStateUsing(function ($state) {
                        return match ($state) {
                            'grid_4' => 'Grid 4',
                            'grid_6' => 'Grid 6',
                            'grid_8' => 'Grid 8',
                            'scroll' => 'Scroll',
                            default => ucfirst(str_replace('_', ' ', $state)),
                        };
                    })
                    ->color(function ($state) {
                        return match ($state) {
                            'grid_4' => 'success',
                            'grid_6' => 'info',
                            'grid_8' => 'warning',
                            'scroll' => 'gray',
                            default => 'gray',
                        };
                    }),

                TextColumn::make('sort_order')
                    ->label('Position')
                    ->sortable(),

                ToggleColumn::make('is_active'),

            ])
            // ->defaultSort('sort_order')
            ->paginated(false) // IMPORTANT for smooth drag
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
