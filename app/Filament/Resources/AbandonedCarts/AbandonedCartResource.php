<?php

namespace App\Filament\Resources\AbandonedCarts;

use App\Filament\Resources\AbandonedCarts\Pages\ManageAbandonedCarts;
use App\Models\AbandonedCheckout;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Mail;

class AbandonedCartResource extends Resource
{
    protected static string|\UnitEnum|null $navigationGroup = 'Store';

    protected static ?string $navigationLabel = 'Abandoned Carts';

    protected static ?int $navigationSort = 6;

    protected static ?string $model = AbandonedCheckout::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShoppingCart;

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('last_active_at', 'desc')
            ->columns([
                TextColumn::make('name')
                    ->label('Customer')
                    ->searchable()
                    ->default(fn($record) => $record->user?->name ?? 'Guest User')
                    ->description(function (AbandonedCheckout $record) {
                        $contact = [];
                        if ($record->email) $contact[] = $record->email;
                        if ($record->phone) $contact[] = '📞 ' . $record->phone;
                        return implode(' • ', $contact) ?: 'Session: ' . substr($record->session_id, 0, 10) . '...';
                    }),

                TextColumn::make('city')
                    ->label('Location')
                    ->formatStateUsing(fn($record) => $record->city ? "{$record->city} ({$record->postal_code})" : 'Unknown')
                    ->color('gray'),

                TextColumn::make('total_qty')
                    ->label('Items')
                    ->alignCenter(),

                TextColumn::make('total_value')
                    ->label('Cart Value')
                    ->money('INR')
                    ->color('warning')
                    ->weight('bold'),

                TextColumn::make('last_active_at')
                    ->label('Abandoned At')
                    ->dateTime('d M Y, h:i A')
                    ->sortable(),
            ])
            ->recordActions([
                // 1. WhatsApp Recovery Action
                Action::make('sendWhatsApp')
                    ->label('WhatsApp')
                    ->icon('heroicon-m-chat-bubble-oval-left-ellipsis')
                    ->color('success')
                    ->visible(fn(AbandonedCheckout $record) => ! empty($record->phone))
                    ->url(function (AbandonedCheckout $record) {
                        $phone = preg_replace('/[^0-9]/', '', $record->phone);
                        if (strlen($phone) === 10) {
                            $phone = '91' . $phone;
                        }

                        $name = $record->name ?: 'there';
                        $checkoutUrl = route('front.checkout', ['recover' => $record->recovery_token]);

                        $message = rawurlencode(
                            "Hi {$name}! 🐾 You left some pet favorites in your cart at Wooflix (Total: ₹" . number_format($record->total_value) . ").\n\n" .
                                "Complete your order here:\n{$checkoutUrl}"
                        );

                        return "https://wa.me/{$phone}?text={$message}";
                    }, shouldOpenInNewTab: true),

                // 2. Email Recovery Action
                Action::make('sendRecoveryEmail')
                    ->label('Email')
                    ->icon('heroicon-m-envelope')
                    ->color('warning')
                    ->visible(fn(AbandonedCheckout $record) => ! empty($record->email))
                    ->action(function (AbandonedCheckout $record) {
                        $recipient = $record->email;

                        Mail::send('emails.abandoned-cart', [
                            'checkout' => $record,
                            'url'      => route('front.checkout', ['recover' => $record->recovery_token]),
                        ], function ($message) use ($recipient) {
                            $message->to($recipient)->subject('🐾 Did you forget something for your pet?');
                        });

                        Notification::make()
                            ->title('Recovery email sent to ' . $recipient)
                            ->success()
                            ->send();
                    }),

                DeleteAction::make()
                    ->requiresConfirmation(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageAbandonedCarts::route('/'),
        ];
    }
}
