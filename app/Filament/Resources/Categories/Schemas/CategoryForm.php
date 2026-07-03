<?php

namespace App\Filament\Resources\Categories\Schemas;

use App\Models\ProductFilterTag;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Category Details')
                    ->schema([

                        Grid::make(2)
                            ->schema([

                                Select::make('parent_id')
                                    ->label('Parent Category')
                                    ->relationship(
                                        'parent', 
                                        'name', 
                                        fn (Builder $query, Select $component) => $query
                                            ->select('categories.*') // ðŸŸ¢ Protects primary model columns from overwrite conflicts
                                            ->leftJoin('product_filter_tags', 'categories.pet_type_tag_id', '=', 'product_filter_tags.id') // ðŸŸ¢ Explicitly join the tags table
                                            ->with('petType') // Keeps the badge option label fast and efficient
                                            ->whereNull('categories.parent_id') // Only show top-level records as parent options
                                            ->when($component->getRecord(), fn($q, $record) => $q->where('categories.id', '!=', $record->id))
                                    )
                                    ->getOptionLabelFromRecordUsing(function ($record) {
                                        $petTypeName = $record->petType?->name;
                                        $petTypeBadge = $petTypeName ? '[' . Str::headline($petTypeName) . '] ' : '';
                                        return "{$petTypeBadge}{$record->name}";
                                    })
                                    // ðŸŸ¢ FIXED FOR POSTGRESQL: Use explicit table names to eliminate SQL ambiguities
                                    ->searchable(['categories.name', 'product_filter_tags.name']) 
                                    ->preload()
                                    ->nullable(),

                                Select::make('pet_type_tag_id')
                                    ->label('Pet Type')
                                    ->relationship(
                                        name: 'petType',
                                        modifyQueryUsing: fn($query) =>
                                        $query->where('type', 'pet_type'),
                                        titleAttribute: 'name'
                                    )
                                    ->options(
                                        ProductFilterTag::where('type', 'pet_type')
                                            ->pluck('name', 'id')
                                    )
                                    ->searchable()
                                    ->preload(),

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

                                FileUpload::make('image')
                                    ->label('Category Image')
                                    ->image()
                                    ->directory('categories')
                                    ->imageEditor()
                                    ->nullable(),

                                FileUpload::make('desktop_banner')
                                    ->label('Desktop Banner')
                                    ->image()
                                    ->directory('Categories/banner/desktop')
                                    ->imageEditor()
                                    ->nullable(),

                                FileUpload::make('mobile_banner')
                                    ->label('Mobile Banner')
                                    ->image()
                                    ->directory('categories/banner/mobile')
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
