<?php

namespace App\Filament\Resources\FooterLinkGroups;

use App\Filament\Resources\FooterLinkGroups\Pages\CreateFooterLinkGroup;
use App\Filament\Resources\FooterLinkGroups\Pages\EditFooterLinkGroup;
use App\Filament\Resources\FooterLinkGroups\Pages\ListFooterLinkGroups;
use App\Filament\Resources\FooterLinkGroups\Schemas\FooterLinkGroupForm;
use App\Filament\Resources\FooterLinkGroups\Tables\FooterLinkGroupsTable;
use App\Models\FooterLinkGroup;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class FooterLinkGroupResource extends Resource
{
    protected static string|\UnitEnum|null $navigationGroup = 'Administration';

    protected static ?string $navigationLabel = 'Footer Links';

    protected static ?int $navigationSort = 6;

    protected static ?string $model = FooterLinkGroup::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedLink;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return FooterLinkGroupForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FooterLinkGroupsTable::configure($table);
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
            'index' => ListFooterLinkGroups::route('/'),
            'create' => CreateFooterLinkGroup::route('/create'),
            'edit' => EditFooterLinkGroup::route('/{record}/edit'),
        ];
    }
}
