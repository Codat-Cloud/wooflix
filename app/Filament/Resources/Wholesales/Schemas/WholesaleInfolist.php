<?php

namespace App\Filament\Resources\Wholesales\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class WholesaleInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('full_name'),
                TextEntry::make('business_name'),
                TextEntry::make('email')
                    ->label('Email address'),
                TextEntry::make('phone'),
                TextEntry::make('business_type'),
                TextEntry::make('gst_number')
                    ->placeholder('-'),
                TextEntry::make('address')
                    ->columnSpanFull(),
                TextEntry::make('city'),
                TextEntry::make('state'),
                TextEntry::make('postal_code'),
                TextEntry::make('monthly_quantity')
                    ->placeholder('-'),
                IconEntry::make('sells_pet_products')
                    ->boolean(),
                TextEntry::make('brands')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('message')
                    ->placeholder('-')
                    ->columnSpanFull(),
                IconEntry::make('consent')
                    ->boolean(),
                TextEntry::make('status'),
                TextEntry::make('source'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
