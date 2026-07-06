<?php

namespace App\Filament\Resources\HomeSections\Schemas;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
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

                                    if ($type === 'tabbed_category_products') {
                                        return ['scroll' => 'Deals Slider'];
                                    }

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
                                Grid::make(2)
                                    ->schema([
                                        Select::make('item_id')
                                            ->label(fn($get) => $get('../../type') === 'tabbed_category_products' ? 'Select Category (Tab)' : 'Select Item')
                                            ->options(function ($get) {
                                                $type = $get('../../type');
                                                
                                                // 1. BRAND OPTIONS
                                                if ($type === 'brand') {
                                                    return Brand::pluck('name', 'id');
                                                }
                                                
                                                // 2. CATEGORY OPTIONS (With Pet Types + Parent Category support)
                                                if ($type === 'category' || $type === 'tabbed_category_products') {
                                                    return Category::query()
                                                        ->select('categories.*', 'product_filter_tags.name as pet_name')
                                                        ->leftJoin('product_filter_tags', 'categories.pet_type_tag_id', '=', 'product_filter_tags.id')
                                                        ->with('parent') // Eager load parent to accurately represent subcategories
                                                        ->get()
                                                        ->mapWithKeys(function ($category) {
                                                            // Generate clean tag string: "[Dog] " or "[Cat] "
                                                            $petTypeBadge = $category->pet_name ? '[' . \Illuminate\Support\Str::headline($category->pet_name) . '] ' : '';
                                                            
                                                            // Build full structural label trail
                                                            $label = $category->parent
                                                                ? "{$petTypeBadge}{$category->parent->name} — {$category->name}"
                                                                : "{$petTypeBadge}{$category->name}";
                                                                
                                                            return [$category->id => $label];
                                                        })
                                                        ->toArray();
                                                }
                                                
                                                return [];
                                            })
                                            ->required()
                                            ->searchable() // 🟢 Indexes the custom label string automatically on the client side
                                            ->live(),

                                        TextInput::make('title')
                                            ->label('Custom Title (Icon + Text)')
                                            ->placeholder('e.g., 🧶 Cat Toys')
                                            ->required()
                                            ->helperText('This title will appear on the Tab button.'),
                                    ]),

                                Select::make('featured_products')
                                    ->label('Select Products for this Tab')
                                    ->multiple()
                                    ->searchable()
                                    ->preload()
                                    ->maxItems(12)
                                    ->columnSpanFull()
                                    ->visible(fn($get) => $get('../../type') === 'tabbed_category_products')
                                    ->options(function ($get) {
                                        $categoryId = $get('item_id');
                                        if (!$categoryId) return [];

                                        // 🟢 FIXED: Scans pivot relations instead of missing category_id column
                                        return Product::whereHas('categories', function ($q) use ($categoryId) {
                                            $q->where('categories.id', $categoryId);
                                        })->pluck('name', 'id');
                                    })
                                    ->afterStateHydrated(function (Select $component, $state, $get) {
                                        $categoryId = $get('item_id');
                                        if ($categoryId) {
                                            // 🟢 FIXED: Qualified column structures for clean hydration matching
                                            $featured = Product::whereHas('categories', function ($q) use ($categoryId) {
                                                $q->where('categories.id', $categoryId);
                                            })
                                            ->where('products.is_featured', true)
                                            ->pluck('products.id')
                                            ->toArray();
                                            
                                            $component->state($featured);
                                        }
                                    })
                                    ->afterStateUpdated(function ($state, $get) {
                                        $categoryId = $get('item_id');
                                        if ($categoryId) {
                                            // 🟢 FIXED: Relational update constraints protecting site metrics
                                            Product::whereHas('categories', function ($q) use ($categoryId) {
                                                $q->where('categories.id', $categoryId);
                                            })->update(['is_featured' => false]);

                                            if (!empty($state)) {
                                                Product::whereIn('id', $state)->update(['is_featured' => true]);
                                            }
                                        }
                                    }),

                                FileUpload::make('image')
                                    ->image()
                                    ->disk('public')
                                    ->directory('home')
                                    ->columnSpanFull()
                                    ->hidden(fn($get) => $get('../../type') === 'tabbed_category_products')
                                    ->required(fn($get) => $get('../../type') !== 'tabbed_category_products'),
                            ])
                    ])
            ]);
    }
}