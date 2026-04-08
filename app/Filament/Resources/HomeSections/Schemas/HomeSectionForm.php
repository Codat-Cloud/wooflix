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
                Section::make('Section Settings')
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
                                    'tabbed_category_products' => 'Deals (Tabbed)',
                                ])
                                ->required()
                                ->live(),

                            Select::make('layout')
                                ->options(function ($get) {
                                    $type = $get('type');

                                    // Deals (Tabbed) ONLY gets the Scroll layout
                                    if ($type === 'tabbed_category_products') {
                                        return ['scroll' => 'Deals Slider'];
                                    }

                                    // Brands, Categories, and Products get Grid 4 or Grid 6
                                    // Note: Grid 6 will be scrollable on mobile automatically in our CSS
                                    return [
                                        'grid_4' => 'Grid 4',
                                        'grid_6' => 'Grid 6',
                                    ];
                                })
                                ->default('grid_6')
                                ->required()
                                ->live(),

                            TextInput::make('sort_order')
                                ->numeric()
                                ->default(0),
                        ]),
                    ]),

                Section::make('Items')
                    ->description('Add Categories (Tabs) and pick Featured Products for Deals')
                    ->schema([
                        Repeater::make('items')
                            ->relationship()
                            ->reorderable()
                            ->orderColumn('sort_order')
                            ->itemLabel(fn($state) => $state['title'] ?? 'Section Item')
                            ->schema([
                                // ROW 1: Category and Title
                                Grid::make(2)
                                    ->schema([
                                        Select::make('item_id')
                                            ->label(fn($get) => $get('../../type') === 'tabbed_category_products' ? 'Select Category (Tab)' : 'Select Item')
                                            ->options(function ($get) {
                                                $type = $get('../../type');
                                                if ($type === 'brand') return Brand::pluck('name', 'id');
                                                if ($type === 'category' || $type === 'tabbed_category_products') {
                                                    return Category::pluck('name', 'id');
                                                }
                                                return [];
                                            })
                                            ->required()
                                            ->searchable()
                                            ->live(),

                                        TextInput::make('title')
                                            ->label('Custom Title (Icon + Text)')
                                            ->placeholder('e.g., 🧶 Cat Toys')
                                            ->required()
                                            ->helperText('This title will appear on the Tab button.'),
                                    ]),

                                // ROW 2: Product Multi-select (Full Width)
                                // Only visible when 'Deals (Tabbed)' is selected as the Section Type
                                Select::make('featured_products')
                                    ->label('Select Products for this Tab')
                                    ->multiple()
                                    ->searchable()
                                    ->preload()
                                    ->maxItems(12)
                                    ->columnSpanFull() // Makes it take the whole second row
                                    ->visible(fn($get) => $get('../../type') === 'tabbed_category_products')
                                    ->options(function ($get) {
                                        $categoryId = $get('item_id');
                                        if (!$categoryId) return [];

                                        return \App\Models\Product::where('category_id', $categoryId)
                                            ->pluck('name', 'id');
                                    })
                                    // Sync logic for is_featured column
                                    ->afterStateHydrated(function (Select $component, $state, $get) {
                                        $categoryId = $get('item_id');
                                        if ($categoryId) {
                                            $featured = \App\Models\Product::where('category_id', $categoryId)
                                                ->where('is_featured', true)
                                                ->pluck('id')
                                                ->toArray();
                                            $component->state($featured);
                                        }
                                    })
                                    ->afterStateUpdated(function ($state, $get) {
                                        $categoryId = $get('item_id');
                                        if ($categoryId) {
                                            \App\Models\Product::where('category_id', $categoryId)->update(['is_featured' => false]);
                                            if (!empty($state)) {
                                                \App\Models\Product::whereIn('id', $state)->update(['is_featured' => true]);
                                            }
                                        }
                                    }),

                                // FileUpload for non-deal sections (Brands/General Categories)
                                FileUpload::make('image')
                                    ->image()
                                    ->directory('home')
                                    ->columnSpanFull()
                                    ->hidden(fn($get) => $get('../../type') === 'tabbed_category_products')
                                    ->required(fn($get) => $get('../../type') !== 'tabbed_category_products'),
                            ])
                    ])
            ]);
    }
}
