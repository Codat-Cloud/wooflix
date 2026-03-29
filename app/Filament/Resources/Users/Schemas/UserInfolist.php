<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                                TextEntry::make('name'),
                TextEntry::make('email')
                    ->label('Email address'),
                // TextEntry::make('email_verified_at')
                //     ->dateTime()
                //     ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),


                // ================= PROFILE =================
                // Section::make('Customer Profile')
                //     ->schema([

                //         TextEntry::make('name'),

                //         TextEntry::make('email'),

                //         TextEntry::make('created_at')
                //             ->label('Joined')
                //             ->dateTime(),

                //     ])
                //     ->columns(2),

                    // ================= STATS =================
                Section::make('Customer Stats')
                    ->schema([

                        TextEntry::make('orders_count')
                            ->label('Total Orders'),

                        TextEntry::make('cart_items_count')
                            ->label('Cart Items'),

                        TextEntry::make('wishlist_count')
                            ->label('Wishlist Items'),

                    ])
                    ->columns(3),

                // ================= ADDRESSES =================
                Section::make('Addresses')
                    ->schema([

                        RepeatableEntry::make('addresses')
                            ->schema([

                                TextEntry::make('name')
                                    ->label('Name'),

                                TextEntry::make('phone'),

                                TextEntry::make('address_line1')
                                    ->label('Address'),

                                TextEntry::make('city'),

                                TextEntry::make('state'),

                                TextEntry::make('postal_code'),

                                TextEntry::make('is_default')
                                    ->badge()
                                    ->label('Default'),

                            ])
                            ->columns(7)

                    ]),



            ]);
    }
}
