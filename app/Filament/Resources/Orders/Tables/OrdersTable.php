<?php

namespace App\Filament\Resources\Orders\Tables;

use App\Models\Order;
use App\Services\ShiprocketOrderService;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // ORDER NUMBER
                TextColumn::make('order_number')
                    ->label('Order')
                    ->searchable()
                    ->sortable(),

                // CUSTOMER
                // TextColumn::make('user.name')
                //     ->label('Customer')
                //     ->searchable(),

                // TOTAL
                TextColumn::make('total_amount')
                    ->label('Amount')
                    ->formatStateUsing(fn($state) => 'Rs. ' . number_format($state, 2))
                    ->sortable(),

                // ORDER STATUS
                TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'warning',
                        'confirmed' => 'primary',
                        'shipped' => 'info',
                        'delivered' => 'success',
                        'cancelled' => 'danger',
                        default => 'gray',
                    })
                    ->icon(fn(string $state): string => match ($state) {
                        'pending' => 'heroicon-m-clock',
                        'confirmed' => 'heroicon-m-check-circle',
                        'shipped' => 'heroicon-m-truck',
                        'delivered' => 'heroicon-m-shopping-bag',
                        'cancelled' => 'heroicon-m-x-circle',
                        default => 'heroicon-m-question-mark-circle',
                    }),

                // PAYMENT STATUS
                TextColumn::make('payment_status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'warning',
                        'paid' => 'success',
                        'failed' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn(string $state): string => ucfirst($state)),

                // DATE
                TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime('d M Y, h:i A')
                    ->sortable(),

            ])
            ->defaultSort('created_at', 'desc')

            ->filters([
                //
            ])
            ->recordActions([
                // ViewAction::make(),
                // EditAction::make(),

                Action::make('print_shipping_label')
                    ->label('Shipping Label')
                    ->icon('heroicon-o-printer')
                    ->color('warning')

                    // Explicit array mapping guarantees segments are parsed correctly
                    ->url(fn(Order $record): string => route('front.shippingLabel', ['order' => $record->id]))

                    ->openUrlInNewTab(),

                Action::make('generateShipment')

                    ->label('Generate Shipment')

                    ->icon('heroicon-o-truck')

                    ->color('success')

                    ->form([

                        TextInput::make('weight')
                            ->numeric()
                            ->required(),

                        TextInput::make('length')
                            ->numeric()
                            ->required(),

                        TextInput::make('width')
                            ->numeric()
                            ->required(),

                        TextInput::make('height')
                            ->numeric()
                            ->required(),

                    ])

                    ->fillForm(function ($record) {

                        $variant = $record->items
                            ->first()?->variant;

                        return [

                            'weight' =>
                            $variant?->weight ?? 0.5,

                            'length' =>
                            $variant?->length ?? 10,

                            'width' =>
                            $variant?->width ?? 10,

                            'height' =>
                            $variant?->height ?? 10,

                        ];
                    })

                    ->action(function ($record, $data) {

                        $service = app(
                            ShiprocketOrderService::class
                        );

                        $response = $service->createShipment(
                            $record,
                            $data
                        );

                        if (
                            !isset($response['shipment_id'])
                        ) {

                            throw new \Exception(
                                $response['message']
                                    ?? 'Shiprocket API failed'
                            );
                        }

                        $record->update([

                            'shiprocket_order_id' =>
                            $response['order_id'] ?? null,

                            'shipment_id' =>
                            $response['shipment_id'] ?? null,

                            'awb_code' =>
                            $response['awb_code'] ?? null,

                            'courier_name' =>
                            $response['courier_name'] ?? null,

                            'label_url' =>
                            $response['label_url'] ?? null,

                            'manifest_url' =>
                            $response['manifest_url'] ?? null,

                        ]);
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    // DeleteBulkAction::make(),
                ]),
            ]);
    }
}
