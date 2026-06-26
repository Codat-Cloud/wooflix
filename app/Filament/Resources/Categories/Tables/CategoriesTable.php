<?php

namespace App\Filament\Resources\Categories\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class CategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->label('Image')
                    ->circular(),
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('parent.name')
                    ->label('Parent Category')
                    ->searchable()
                    ->sortable()
                    ->placeholder('None (Top Level)'),
                TextColumn::make('slug')
                    ->searchable(),
                TextColumn::make('petType.name')
                    ->label('Pet Type')
                    ->badge()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('products_count')
                    ->counts('products')
                    ->label('Total Products')
                    ->badge()
                    ->color('success')
                    ->sortable()
            ])
            ->filters([
                SelectFilter::make('petType')
                    ->relationship('petType', 'name', fn ($query) => $query->where('type', 'pet_type'))
                    ->label('Filter by Pet Type')
                    ->preload(),
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
