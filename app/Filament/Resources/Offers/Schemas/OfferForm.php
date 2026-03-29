<?php

namespace App\Filament\Resources\Offers\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class OfferForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Offer Card')
                ->schema([

                    TextInput::make('title')
                        ->label('Title / Alt Text')
                        ->placeholder('Dog Food, Cat Toys'),

                    FileUpload::make('image')
                        ->label('Offer Image')
                        ->image()
                        ->disk('public')
                        ->directory('offers')
                        ->required()
                        ->helperText('Use square or card format image'),

                    TextInput::make('link')
                        ->label('Redirect URL')
                        ->placeholder('/category/dog-food'),

                    TextInput::make('sort_order')
                        ->numeric()
                        ->default(0),

                    Toggle::make('is_active')
                        ->default(true),

                ])
            ]);
    }
}
