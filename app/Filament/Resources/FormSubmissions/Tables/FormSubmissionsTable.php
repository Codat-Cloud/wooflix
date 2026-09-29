<?php

namespace App\Filament\Resources\FormSubmissions\Tables;

use App\Models\FormSubmission;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class FormSubmissionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('form_type')
                    ->label('Form Type')
                    ->badge()
                    ->formatStateUsing(fn(string $state): string => ucfirst($state))
                    ->color(fn(string $state): string => match (strtolower($state)) {
                        'contact'   => 'primary',
                        'wholesale' => 'success',
                        'support'   => 'info',
                        default     => 'gray',
                    })
                    ->searchable()
                    ->sortable(),
                TextColumn::make('data.name')
                    ->label('Sender Name')
                    ->default(fn($record) => $record->data['full_name'] ?? $record->data['first_name'] ?? '—')
                    ->weight(FontWeight::SemiBold)
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->where('data->name', 'ILIKE', "%{$search}%")
                            ->orWhere('data->full_name', 'ILIKE', "%{$search}%");
                    }),

                // Extract email identifier from dynamic JSON
                TextColumn::make('data.email')
                    ->label('Email')
                    ->default(fn($record) => $record->data['email_address'] ?? '—')
                    ->icon('heroicon-m-envelope')
                    ->copyable()
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->where('data->email', 'ILIKE', "%{$search}%")
                            ->orWhere('data->email_address', 'ILIKE', "%{$search}%");
                    }),

                // Associated Page (if passed via _page_id)
                TextColumn::make('page.title')
                    ->label('Source Page')
                    ->placeholder('Direct / Unknown')
                    ->badge()
                    ->color('gray')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('ip_address')
                    ->searchable(),
                IconColumn::make('is_read')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-o-envelope-open')
                    ->falseIcon('heroicon-s-envelope')
                    ->trueColor('gray')
                    ->falseColor('warning')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('form_type')
                    ->label('Form Type')
                    ->options([
                        'contact'   => 'Contact Forms',
                        'wholesale' => 'Wholesale Forms',
                        'support'   => 'Support Forms',
                    ]),

                TernaryFilter::make('is_read')
                    ->label('Read Status')
                    ->placeholder('All Submissions')
                    ->trueLabel('Read Only')
                    ->falseLabel('Unread Only'),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                Action::make('toggleRead')
                    ->label(fn(FormSubmission $record) => $record->is_read ? 'Mark Unread' : 'Mark Read')
                    ->icon(fn(FormSubmission $record) => $record->is_read ? 'heroicon-o-envelope' : 'heroicon-o-envelope-open')
                    ->color('gray')
                    ->action(fn(FormSubmission $record) => $record->update(['is_read' => ! $record->is_read])),

                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                BulkAction::make('markAsRead')
                    ->label('Mark as Read')
                    ->icon('heroicon-o-check-circle')
                    ->action(fn($records) => $records->each->update(['is_read' => true])),
                ]),
            ]);
    }
}
