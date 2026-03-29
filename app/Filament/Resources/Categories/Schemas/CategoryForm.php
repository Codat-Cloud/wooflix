<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
        ->components([

            Section::make()
                ->schema([

                    Grid::make(2)
                        ->schema([

                            Select::make('parent_id')
                                ->label('Parent Category')
                                ->relationship('parent', 'name')
                                ->searchable()
                                ->nullable(),

                            TextInput::make('name')
                                ->required()
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn ($state, $set) =>
                                    $set('slug', Str::slug($state))
                                ),

                            TextInput::make('slug')
                                ->nullable()
                                ->unique(ignoreRecord: true),

                            FileUpload::make('image')
                                ->image()
                                ->directory('categories')
                                ->imageEditor()
                                ->nullable(),
                        ]),

                    Textarea::make('description')
                        ->columnSpanFull(),

                ]),

            Section::make('SEO')
                ->schema([

                    Grid::make(2)
                        ->schema([

                            TextInput::make('meta_title'),

                            Textarea::make('meta_description'),

                        ])

                ]),

        ]);
    }
}
