<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Models\ProductFilterTag;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            // Standard top-level grid layout separating core data from action toggles
            Grid::make([
                'default' => 1,
                'lg' => 3, // 3 Columns on large screens
            ])->schema([

                // ================= MAIN TABS COLUMN (Left Side - 2/3 Width) =================
                Tabs::make('Product Management')
                    ->tabs([

                        // TAB 1: CORE DATA DETAILS
                        Tab::make('General Info')
                            ->icon('heroicon-m-document-text')
                            ->schema([
                                TextInput::make('name')
                                    ->required()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(
                                        fn($state, $set) => $set('slug', Str::slug($state))
                                    ),

                                TextInput::make('slug')
                                    ->nullable()
                                    ->helperText('This will be the product URL path string.')
                                    ->unique(ignoreRecord: true),

                                Grid::make(2)->schema([
                                    Select::make('brand_id')
                                        ->relationship('brand', 'name')
                                        ->preload()
                                        ->searchable(),

                                Select::make('categories')
                                    ->relationship(
                                        'categories', 
                                        'name', 
                                        fn (Builder $query) => $query
                                            ->select('categories.*') // 🟢 Keep category data clean from join column overwrites
                                            ->leftJoin('product_filter_tags', 'categories.pet_type_tag_id', '=', 'product_filter_tags.id') // 🟢 Explicitly join the tag table
                                            ->with(['parent', 'petType'])
                                    )
                                    ->multiple()
                                    ->getOptionLabelFromRecordUsing(function ($record) {
                                        $petTypeName = $record->petType?->name;
                                        $petTypeBadge = $petTypeName ? '[' . Str::headline($petTypeName) . '] ' : '';
                                        return $record->parent
                                            ? "{$petTypeBadge}{$record->parent->name} — {$record->name}"
                                            : "{$petTypeBadge}{$record->name}";
                                    })
                                    // 🟢 FIXED FOR POSTGRESQL: Swap dot-notation for real database table and column names
                                    ->searchable(['categories.name', 'product_filter_tags.name'])
                                    ->preload()
                                    ->required(),
                                ]),

                                RichEditor::make('short_description')
                                    ->label('Short Summary / Highlights'),

                                RichEditor::make('description')
                                    ->label('Detailed Specifications')
                                    ->columnSpanFull(),
                            ]),

                        // TAB 2: MARKETING & CROSS-SELLING IMAGES GRID
                        Tab::make('Media Gallery')
                            ->icon('heroicon-m-photo')
                            ->schema([
                                Grid::make(2)->schema([
                                    FileUpload::make('main_image')
                                        ->label('Main Image')
                                        ->image()
                                        ->disk('public')
                                        ->directory('products')
                                        ->required()
                                        ->imagePreviewHeight('150'),

                                    FileUpload::make('size_chart_image')
                                        ->label('Sizing Matrix Chart')
                                        ->disk('public')
                                        ->visibility('public')
                                        ->image()
                                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/gif'])
                                        ->directory('products/size-charts')
                                        ->nullable()
                                        ->imagePreviewHeight('150'),
                                ]),

                                Repeater::make('galleryImages')
                                    ->label('Carousel Slide Gallery')
                                    ->relationship('galleryImages')
                                    ->schema([
                                        FileUpload::make('image')
                                            ->image()
                                            ->disk('public')
                                            ->visibility('public')
                                            ->directory('products/gallery')
                                    ])->grid(3)
                                    // 🔍 Force the type to 'gallery' before creation
                                    ->mutateRelationshipDataBeforeCreateUsing(function (array $data): array {
                                        $data['type'] = 'gallery';
                                        return $data;
                                    })
                                    // 🔍 Force the type to 'gallery' during updates
                                    ->mutateRelationshipDataBeforeSaveUsing(function (array $data): array {
                                        $data['type'] = 'gallery';
                                        return $data;
                                    }),

                                Repeater::make('infographicImages')
                                    ->relationship('infographicImages')
                                    ->schema([
                                        FileUpload::make('image')
                                            ->image()
                                            ->disk('public')
                                            ->visibility('public')
                                            ->directory('products/infographics')
                                            ->required(),
                                    ])
                                    ->label('Description Infographics (Optional)')
                                    ->grid(2)
                                    // 🔍 Force the type to 'infographic' before creation
                                    ->mutateRelationshipDataBeforeCreateUsing(function (array $data): array {
                                        $data['type'] = 'infographic';
                                        return $data;
                                    })
                                    // 🔍 Force the type to 'infographic' during updates
                                    ->mutateRelationshipDataBeforeSaveUsing(function (array $data): array {
                                        $data['type'] = 'infographic';
                                        return $data;
                                    }),
                            ]),

                        // TAB 3: DYNAMIC FILTER TAG ASSOCIATIONS
                        Tab::make('Product Filters')
                            ->icon('heroicon-m-funnel')
                            ->schema(
                                ProductFilterTag::query()
                                    ->where('is_active', true)
                                    ->get()
                                    ->groupBy('type')
                                    ->map(function ($filters, $type) {
                                        return CheckboxList::make("filters.$type")
                                            ->label(str($type)->replace('_', ' ')->title())
                                            ->options($filters->pluck('name', 'id')->toArray())
                                            ->columns(3); // Increased columns for space efficiency
                                    })
                                    ->values()
                                    ->toArray()
                            ),

                        // TAB 4: MARKETING CROSS-SELLING SETTINGS
                        Tab::make('Cross-Selling')
                            ->icon('heroicon-m-shopping-cart')
                            ->schema([
                                Select::make('frequentlyBought')
                                    ->relationship('frequentlyBought', 'name')
                                    ->multiple()
                                    ->searchable()
                                    ->preload()
                                    ->maxItems(4)
                                    ->hint('Select a maximum of 4 complementary items.')
                            ]),

                        // TAB 5: ADVANCED META / SEO SCRIPTS DATA
                        Tab::make('SEO & Tracking')
                            ->icon('heroicon-m-globe-alt')
                            ->schema([
                                TextInput::make('meta_title')->label('SEO Title Tag'),
                                Textarea::make('meta_description')->label('SEO Meta Description'),
                                Textarea::make('custom_tracking_script')
                                    ->label('Custom Page Pixels')
                                    ->helperText('Paste tracking codes or analytics events scripts here.'),
                            ]),
                    ])->columnSpan(['lg' => 2]),

                // ================= SIDEBAR UTILITY PANEL (Right Side - 1/3 Width) =================
                Grid::make(1)->schema([
                    Section::make('Publish Status')
                        ->schema([
                            Toggle::make('is_active')
                                ->label('Published on Storefront')
                                ->default(true),
                        ]),

                    Section::make('Logistics Codes')
                        ->schema([
                            TextInput::make('asin')
                                ->label('ASIN Code'),
                            TextInput::make('hsn')
                                ->label('HSN / Tax Code'),
                        ]),
                ])->columnSpan(['lg' => 1]),

            ])->columnSpanFull(),
        ]);
    }
}
