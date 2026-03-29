<?php

namespace App\Filament\Resources\Banners\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BannerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Banner Details')
                    ->schema([

                        TextInput::make('title')
                            ->placeholder('Optional internal name'),

                        FileUpload::make('desktop_image')
                            ->label('Desktop Banner')
                            ->image()
                            ->disk('public')
                            ->directory('banners')
                            ->helperText('Recommended: 4320x900px')
                            ->required(),

                        FileUpload::make('mobile_image')
                            ->label('Mobile Banner')
                            ->image()
                            ->disk('public')
                            ->directory('banners')
                            ->helperText('Recommended: 1080x918px')
                            ->required(),

                        TextInput::make('link')
                            ->label('Redirect URL')
                            ->placeholder('https://example.com/product'),

                        TextInput::make('sort_order')
                            ->numeric()
                            ->default(0),

                        Toggle::make('is_active')
                            ->default(true),

                    ])
            ]);
    }
}
