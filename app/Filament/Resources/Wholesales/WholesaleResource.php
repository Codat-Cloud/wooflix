<?php

namespace App\Filament\Resources\Wholesales;

use App\Filament\Resources\Wholesales\Pages\CreateWholesale;
use App\Filament\Resources\Wholesales\Pages\EditWholesale;
use App\Filament\Resources\Wholesales\Pages\ListWholesales;
use App\Filament\Resources\Wholesales\Pages\ViewWholesale;
use App\Filament\Resources\Wholesales\Schemas\WholesaleForm;
use App\Filament\Resources\Wholesales\Schemas\WholesaleInfolist;
use App\Filament\Resources\Wholesales\Tables\WholesalesTable;
use App\Models\Wholesale;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class WholesaleResource extends Resource
{
    protected static string|\UnitEnum|null $navigationGroup = 'Administration';

    protected static ?string $navigationLabel = 'Wholesale';

    protected static ?int $navigationSort = 7;

    protected static ?string $model = Wholesale::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedInboxStack;

    protected static ?string $recordTitleAttribute = 'full_name';

    public static function form(Schema $schema): Schema
    {
        return WholesaleForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return WholesaleInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WholesalesTable::configure($table);
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
            'index' => ListWholesales::route('/'),
            // 'create' => CreateWholesale::route('/create'),
            'view' => ViewWholesale::route('/{record}'),
            // 'edit' => EditWholesale::route('/{record}/edit'),
        ];
    }
}
