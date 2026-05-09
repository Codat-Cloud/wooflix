<?php

namespace App\Filament\Resources\ProductFilterTags;

use App\Filament\Resources\ProductFilterTags\Pages\CreateProductFilterTag;
use App\Filament\Resources\ProductFilterTags\Pages\EditProductFilterTag;
use App\Filament\Resources\ProductFilterTags\Pages\ListProductFilterTags;
use App\Filament\Resources\ProductFilterTags\Schemas\ProductFilterTagForm;
use App\Filament\Resources\ProductFilterTags\Tables\ProductFilterTagsTable;
use App\Models\ProductFilterTag;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ProductFilterTagResource extends Resource
{
    protected static string|\UnitEnum|null $navigationGroup = 'Store';

    protected static ?string $navigationLabel = 'Filter Tags';

    protected static ?int $navigationSort = 5;

    protected static ?string $model = ProductFilterTag::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedHashtag;

    protected static ?string $recordTitleAttribute = 'type';

    public static function form(Schema $schema): Schema
    {
        return ProductFilterTagForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProductFilterTagsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProductFilterTags::route('/'),
            'create' => CreateProductFilterTag::route('/create'),
            'edit' => EditProductFilterTag::route('/{record}/edit'),
        ];
    }
}
