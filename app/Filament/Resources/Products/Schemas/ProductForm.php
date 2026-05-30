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

                        // Toggle::make('is_featured')
                        //         ->label('Added In Deals Tab')
                        //         ->default(0),

                        TextInput::make('asin')
                            ->label('ASIN')
                            ->helperText('Applicable to non-variation product.'),

                        TextInput::make('hsn')
                            ->label('HSN Code')
                            ->helperText('Only applicable in invoice'),

                        Select::make('brand_id')
                            ->relationship('brand', 'name')
                            ->preload()
                            ->searchable(),

                        Select::make('category_id')
                            // Eager load both the parent category and the new relational petType tag
                            ->relationship('category', 'name', fn($query) => $query->with(['parent', 'petType']))
                            ->getOptionLabelFromRecordUsing(function ($record) {

                                // 1. Fetch the pet type name from the ProductFilterTag relationship safely
                                $petTypeName = $record->petType?->name;
                                $petTypeBadge = $petTypeName
                                    ? '[' . Str::headline($petTypeName) . '] '
                                    : '';

                                // 2. Build the structural tree path ("Parent — Child" or just "Child")
                                $categoryPath = $record->parent
                                    ? "{$record->parent->name} — {$record->name}"
                                    : $record->name;

                                // 3. Output standard layout template: "[Dog] Food — Dry Food"
                                return "{$petTypeBadge}{$categoryPath}";
                            })
                            // Explicitly add 'petType.name' to the search index matrix mapping
                            ->searchable(['name', 'petType.name'])
                            ->preload()
                            ->columnSpanFull()
                            ->required(),


                        TextInput::make('name')
                            ->required()
                            ->live(onBlur: true)
                            ->columnSpanFull()
                            ->afterStateUpdated(
                                fn($state, $set) =>
                                $set('slug', Str::slug($state))
                            ),

                        TextInput::make('slug')
                            ->nullable()
                            ->columnSpanFull()
                            ->helperText('This will be product url.')
                            ->unique(ignoreRecord: true),


                    ]),

                    RichEditor::make('short_description')
                        ->label('Short Description / Details'),

                    RichEditor::make('description')
                        ->label('Description / Additional Information')
                        ->columnSpanFull(),

                    TextInput::make('meta_title'),
                    Textarea::make('meta_description'),
                    Textarea::make('custom_tracking_script'),



                    // ================ Product Filter ===================

                    Section::make('Select Filters')
                        ->schema(

                            ProductFilterTag::query()
                                ->where('is_active', true)
                                ->get()
                                ->groupBy('type')
                                ->map(function ($filters, $type) {

                                    return CheckboxList::make("filters.$type")
                                        ->label(str($type)->replace('_', ' ')->title())
                                        ->options(
                                            $filters->pluck('name', 'id')->toArray()
                                        )
                                        ->columns(2);
                                })
                                ->values()
                                ->toArray()

                        )
                        ->collapsed()
                        ->collapsible(),

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
