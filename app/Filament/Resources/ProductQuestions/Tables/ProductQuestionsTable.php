<?php

namespace App\Filament\Resources\ProductQuestions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ProductQuestionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('product.name')
                    ->label('Product')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('name')
                    ->label('Customer')
                    ->searchable(),
                TextColumn::make('question')
                    ->limit(40)
                    ->tooltip(fn ($record) => $record->question),
                IconColumn::make('answer')
                    ->label('Answered')
                    ->boolean()
                    ->tooltip(fn ($record) => $record->answer)
                    ->getStateUsing(fn ($record) => !empty($record->answer)),
                IconColumn::make('is_visible')
                    ->label('Live')
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
