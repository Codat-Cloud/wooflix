<?php

namespace App\Filament\Resources\Brands\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class BrandForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make()
                    ->schema([

                        Grid::make(2)
                            ->schema([

                                TextInput::make('name')
                                    ->required()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn ($state, $set) =>
                                        $set('slug', Str::slug($state))
                                    ),

                                TextInput::make('slug')
                                    ->nullable()
                                    ->unique(ignoreRecord: true),

                                    
                                ]),
                                
                        Grid::make(1)
                            ->schema([
                                FileUpload::make('logo')
                                    ->image()
                                    ->directory('brands')
                                    ->imageEditor()
                                    ->nullable(),

                                Textarea::make('description')
                                    ->columnSpanFull(),
        
                                    Toggle::make('is_visible')
                                            ->label('Visibility')
                                            ->default(true),
                            ]),


                    ]),

                Section::make('SEO')
                    ->schema([

                        Grid::make(2)
                            ->schema([

                                TextInput::make('meta_title'),

                                Textarea::make('meta_description'),

                            ]),

                    ]),

            ]);
    }
}
