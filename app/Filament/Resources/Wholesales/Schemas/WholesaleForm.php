<?php

namespace App\Filament\Resources\Wholesales\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class WholesaleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Wholesale')
                    ->schema([

                        TextInput::make('full_name')
                            ->required(),
                        TextInput::make('business_name')
                            ->required(),
                        TextInput::make('email')
                            ->label('Email address')
                            ->email()
                            ->required(),
                        TextInput::make('phone')
                            ->tel()
                            ->required(),
                        TextInput::make('business_type')
                            ->required(),
                        TextInput::make('gst_number'),
                        Textarea::make('address')
                            ->required()
                            ->columnSpanFull(),
                        TextInput::make('city')
                            ->required(),
                        TextInput::make('state')
                            ->required(),
                        TextInput::make('postal_code')
                            ->required(),
                        TextInput::make('products_interested'),
                        TextInput::make('monthly_quantity'),
                        Toggle::make('sells_pet_products')
                            ->required(),
                        Textarea::make('brands')
                            ->columnSpanFull(),
                        TextInput::make('sales_channels'),
                        Textarea::make('message')
                            ->columnSpanFull(),
                        Toggle::make('consent')
                            ->required(),
                        TextInput::make('status')
                            ->required()
                            ->default('new'),
                        TextInput::make('source')
                            ->required()
                            ->default('website'),
                    ])
                    ->columnSpanFull()
                    ->columns(3)

            ]);
    }
}
