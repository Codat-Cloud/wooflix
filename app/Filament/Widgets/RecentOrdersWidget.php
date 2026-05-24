<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Filament\Tables\Columns\TextColumn;

class RecentOrdersWidget extends TableWidget
{
    protected static ?int $sort = 3;

    protected static ?string $heading =
        'Recent Orders';

    public function table(Table $table): Table
    {
        return $table

            ->query(
                Order::latest()
            )

            ->columns([

                TextColumn::make('order_number')
                    ->searchable(),

                TextColumn::make('shipping_name')
                    ->label('Customer'),

                TextColumn::make('total_amount')
                    ->money('INR'),

                TextColumn::make('status')
                    ->badge(),

                TextColumn::make('payment_status')
                    ->badge(),

                TextColumn::make('created_at')
                    ->dateTime(),

            ]);
    }
}