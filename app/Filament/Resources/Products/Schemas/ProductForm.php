<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {

        return $schema->components([

            // ================= BASIC INFO =================
            Section::make('Product Info')
                ->schema([

                    Grid::make(2)->schema([

                        Select::make('category_id')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload() 
                            ->required(),

                        Select::make('brand_id')
                            ->relationship('brand', 'name')
                            ->preload() 
                            ->searchable(),

                        TextInput::make('name')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(
                                fn($state, $set) =>
                                $set('slug', Str::slug($state))
                            ),

                        TextInput::make('slug')
                            ->nullable()
                            ->unique(ignoreRecord: true),

                        Toggle::make('is_active')
                            ->label('Published')
                            ->visible(fn($record) => $record !== null),

                        Toggle::make('is_featured')
                                ->hidden()
                                ->default(0),

                        TextInput::make('base_price')
                            ->required(),

                    ]),

                    RichEditor::make('short_description')
                        ->label('Short Description / Details'),

                    RichEditor::make('description')
                        ->label('Description / Additional Information')
                        ->columnSpanFull(),

                    TextInput::make('meta_title'),
                    Textarea::make('meta_description'),

                ]),

            // ================= PRODUCT IMAGES =================
            Section::make('Product Images')
                ->schema([

                FileUpload::make('main_image')
                    ->label('Main Image')
                    ->image()
                    ->disk('public')
                    ->directory('products')
                    ->imagePreviewHeight('150'),

                    // ================= PRODUCT IMAGES =================
        
                Repeater::make('images')
                    ->relationship()
                    ->schema([
                        FileUpload::make('image')
                            ->image()
                            ->disk('public') 
                            ->directory('products')
                    ])
        
                ]),

                // Section::make('Variations')
                // ->schema([

                //     Repeater::make('variant_inputs')
                //     ->label('Product Variations')
                //     ->schema([

                //         TextInput::make('name')
                //             ->label('Variation Name')
                //             ->placeholder('Flavor / Size / Pack')
                //             ->required(),

                //         TextInput::make('values')
                //             ->label('Values')
                //             ->placeholder('Chocolate, Vanilla/ 200G, 500G/ Pack of 2')
                //             ->required(),

                //     ])
                //     ->defaultItems(1)
                //     // ->columnSpanFull()

                // ])


            // ================= SEO =================
            // Section::make('SEO')
            //     ->schema([



            //     ]),

        ]);
    }
}
