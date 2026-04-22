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

                        Toggle::make('is_active')
                            ->label('Published')
                            ->visible(fn($record) => $record !== null),

                        Toggle::make('is_featured')
                                ->label('Added In Deals Tab')
                                ->default(0),

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

                        TextInput::make('base_price')
                            ->helperText('Maximum Retail Price.')
                            ->prefix('₹')
                            ->required(),
                        TextInput::make('sale_price')
                            ->label('Sale Price')
                            ->prefix('₹')
                            ->helperText('Leave blank if there is no discount.')
                            ->lte('base_price'),

                        TextInput::make('stock')
                            ->label('Total Stock')
                            ->helperText('Applicable to non-variation product.'),

                    ]),

                    RichEditor::make('short_description')
                        ->label('Short Description / Details'),

                    RichEditor::make('description')
                        ->label('Description / Additional Information')
                        ->columnSpanFull(),

                    TextInput::make('meta_title'),
                    Textarea::make('meta_description'),
                    Textarea::make('custom_tracking_script'),

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

        ]);
    }
}
