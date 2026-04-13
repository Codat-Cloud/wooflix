<?php

namespace App\Filament\Resources\Coupons\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Builder;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class CouponsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                ->fontFamily('mono')
                ->searchable()
                ->sortable()
                ->copyable(), // Admin can copy-paste for customers

                TextColumn::make('type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'percentage' => 'info',
                        'fixed' => 'success',
                        'free_shipping' => 'warning',
                    }),

                TextColumn::make('value')
                    ->label('Benefit')
                    ->formatStateUsing(fn ($record) => $record->type === 'percentage' ? $record->value . '%' : '₹' . $record->value)
                    ->sortable(),

                TextColumn::make('usage_count')
                    ->label('Used')
                    ->getStateUsing(fn ($record) => $record->redemptions()->count() . ($record->usage_limit ? " / {$record->usage_limit}" : "")),

                IconColumn::make('is_active')
                    ->boolean()
                    ->label('Active'),

                TextColumn::make('expires_at')
                    ->dateTime('d M, Y')
                    ->color(fn ($state) => $state && $state->isPast() ? 'danger' : null)
                    ->sortable(),
                ])
            ->filters([
                TernaryFilter::make('is_active'),
                Filter::make('expired')
                    ->query(fn (Builder $query): Builder => $query->where('expires_at', '<', now())),
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
