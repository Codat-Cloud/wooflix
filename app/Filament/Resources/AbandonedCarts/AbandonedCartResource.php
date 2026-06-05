<?php

namespace App\Filament\Resources\AbandonedCarts;

use App\Filament\Resources\AbandonedCarts\Pages\ManageAbandonedCarts;
use App\Models\AbandonedCartView;
use App\Models\CartItem;
use App\Models\User;
use App\Mail\AbandonedCartRecover;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Mail;

class AbandonedCartResource extends Resource
{
    protected static string|\UnitEnum|null $navigationGroup = 'Store';

    protected static ?string $navigationLabel = 'Abandoned Carts';

    protected static ?int $navigationSort = 6;

    protected static ?string $model = AbandonedCartView::class; // 🔍 Changed

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShoppingCart;

    public static function table(Table $table): Table
    {
        return $table
            // 🔍 Look at how clean this query is now! No raw SQL strings or subqueries needed here.
            ->columns([
                TextColumn::make('user.name')
                    ->label('Customer')
                    ->default('Anonymous Guest')
                    ->searchable()
                    ->description(fn ($record) => $record->user?->email ?? "Session: " . substr($record->session_id, 0, 12) . '...'),

                TextColumn::make('total_qty')
                    ->label('Total Items')
                    ->alignCenter(),

                TextColumn::make('total_value')
                    ->label('Cart Value')
                    ->money('INR')
                    ->color('warning')
                    ->weight('bold'),

                TextColumn::make('last_activity')
                    ->label('Last Activity')
                    ->dateTime('d M Y, h:i A')
                    ->sortable(),
            ])
            ->filters([])
            ->recordActions([
                Action::make('sendRecoveryEmail')
                    ->label('Email User')
                    ->icon('heroicon-m-envelope')
                    ->color('success')
                    ->visible(fn ($record) => $record->user_id !== null)
                    ->action(function ($record) {
                        $user = User::find($record->user_id);
                        
                        // Query the actual cart items table to send the email layout logs
                        $items = CartItem::where('user_id', $record->user_id)->get();

                        if ($user && $user->email) {
                            Mail::to($user->email)->send(new AbandonedCartRecover($user, $items));
                            
                            Notification::make()
                                ->title('Recovery email successfully delivered to ' . $user->name)
                                ->success()
                                ->send();
                        }
                    }),
                    
                DeleteAction::make()
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        // Purge actual underlying items rows securely
                        CartItem::where('session_id', $record->session_id)
                            ->when($record->user_id, fn($q) => $q->orWhere('user_id', $record->user_id))
                            ->delete();

                        Notification::make()
                            ->title('Abandoned cart items permanently deleted.')
                            ->danger()
                            ->send();
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->action(function ($records) {
                            foreach ($records as $record) {
                                CartItem::where('session_id', $record->session_id)
                                    ->when($record->user_id, fn($q) => $q->orWhere('user_id', $record->user_id))
                                    ->delete();
                            }
                            
                            Notification::make()
                                ->title('Selected abandoned carts purged successfully.')
                                ->success()
                                ->send();
                        }),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageAbandonedCarts::route('/'),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }
}