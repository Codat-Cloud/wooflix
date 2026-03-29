<?php

namespace App\Filament\Resources\HomeSections\Schemas;

use App\Models\Brand;
use App\Models\Category;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class HomeSectionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Section')
                    ->schema([

                        Toggle::make('is_active')
                            ->default(true),

                        Grid::make(2)->schema([

                            TextInput::make('title')
                                ->required()
                                ->placeholder('Try Yum Nums'),

                            TextInput::make('subtitle')
                                ->placeholder('Soft Chews: For ₹199'),

                        ]),

                        Grid::make(3)->schema([

                            Select::make('type')
                                ->options([
                                    'brand' => 'Brand',
                                    'category' => 'Category',
                                    'product' => 'Product',
                                    'tabbed_category_products' => 'Deals (Tabbed)',
                                ])
                                ->required()
                                ->live(),

                            Select::make('layout')
                                ->options([
                                    'scroll' => 'Scroll (brands)',
                                    'grid_4' => 'Grid 4',
                                    'grid_6' => 'Grid 6',
                                    'grid_8' => 'Grid 8',
                                ])
                                ->default('scroll')
                                ->required()
                                ->helperText('Scroll = horizontal slider (best for brands). Grid = fixed layout (best for categories/products).'),

                            TextInput::make('sort_order')
                                ->numeric()
                                ->default(0)
                                ->helperText('Lower numbers appear first on homepage. Example: 0 = top, 10 = below.'),

                        ]),

                    ]),

                Section::make('Items')
                    ->description('Add items for this section')
                    ->schema([

                        Repeater::make('items')
                            ->relationship() // IMPORTANT
                            ->reorderable()  // drag & drop items
                            ->collapsible()
                            ->collapsed()
                            ->defaultItems(1)
                            ->orderColumn('sort_order') // 🔥 THIS IS THE REAL FIX
                            // 🔥 SHOW TITLE IN HEADER
                            ->itemLabel(fn($state) => $state['title'] ?? 'Item')
                            ->schema([

                                // Dynamic select based on type
                                Select::make('item_id')
                                    ->label('Select Item')
                                    ->options(function ($get) {
                                        $type = $get('../../type');

                                        if ($type === 'brand') {
                                            return Brand::pluck('name', 'id');
                                        }

                                        if ($type === 'category') {
                                            return Category::pluck('name', 'id');
                                        }

                                        return [];
                                    })
                                    ->searchable()
                                    ->visible(fn($get) => in_array($get('../../type'), ['brand', 'category'])),

                                TextInput::make('title')
                                    ->placeholder('Override title (optional)'),

                                TextInput::make('link')
                                    ->placeholder('/shop?brand=1'),

                                FileUpload::make('image')
                                    ->image()
                                    ->disk('public')
                                    ->directory('home')
                                    ->helperText('Optional override image. Size: 258x312px')
                                    ->columnSpanFull(),

                                TextInput::make('sort_order')
                                    ->numeric()
                                    ->default(0)
                                    ->columnSpanFull()
                                    ->hidden()
                                    ->helperText('Lower numbers appear first on homepage. Example: 0 = top, 10 = below.'),

                            ])
                            ->columns(2)
                            ->defaultItems(1),

                    ])
            ]);
    }
}
