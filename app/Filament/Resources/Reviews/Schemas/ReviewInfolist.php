<?php

namespace App\Filament\Resources\Reviews\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ReviewInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('product_id')
                    ->numeric(),
                TextEntry::make('user_id')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('customer_name'),
                TextEntry::make('customer_email'),
                TextEntry::make('rating')
                    ->numeric(),
                TextEntry::make('comment')
                    ->columnSpanFull(),
                IconEntry::make('is_approved')
                    ->boolean(),
                IconEntry::make('is_verified_buyer')
                    ->boolean(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
