<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class OrderInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // ================= ORDER INFO =================
                Section::make('Order Details')
                    ->schema([

                        TextEntry::make('order_number')
                            ->label('Order Number'),

                        TextEntry::make('user.name')
                            ->label('Customer'),

                        TextEntry::make('total_amount')
                            ->label('Total Amount')
                            ->formatStateUsing(fn ($state) => 'Rs. ' . number_format($state, 2)),

                        TextEntry::make('status')
                            ->badge(),

                        TextEntry::make('payment_status')
                            ->badge(),

                        TextEntry::make('created_at')
                            ->label('Order Date')
                            ->dateTime('d M Y, h:i A'),

                    ])
                    ->columns(2),

                // ================= ORDER ITEMS =================
                Section::make('Order Items')
                    ->schema([

                        RepeatableEntry::make('items')
                            ->schema([

                                TextEntry::make('name')
                                    ->label('Product'),

                                TextEntry::make('price')
                                    ->formatStateUsing(fn ($state) => 'Rs. ' . number_format($state, 2)),

                                TextEntry::make('quantity'),

                                TextEntry::make('total')
                                    ->state(fn ($record) => $record->price * $record->quantity)
                                    ->label('Total')
                                    ->formatStateUsing(fn ($state) => 'Rs. ' . number_format($state, 2)),

                            ])
                            ->columns(4)

                    ]),

            ]);
    }
}
