<?php

namespace App\Filament\Resources\FormSubmissions\Schemas;

use Filament\Infolists\Components\KeyValueEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class FormSubmissionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Submission Details')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextEntry::make('form_type')
                                    ->label('Form Type')
                                    ->badge()
                                    ->formatStateUsing(fn($state) => ucfirst($state)),

                                TextEntry::make('created_at')
                                    ->label('Received At')
                                    ->dateTime('d M Y, h:i A'),

                                TextEntry::make('page.title')
                                    ->label('Submitted On Page')
                                    ->placeholder('Direct / General'),
                            ]),
                    ]),

                Section::make('Form Answers')
                    ->description('Arbitrary fields submitted from this page.')
                    ->schema([
                        // 🟢 Automatically formats any dynamic JSON fields into a clean 2-column table
                        KeyValueEntry::make('data')
                            ->label('')
                            ->keyLabel('Field Name')
                            ->valueLabel('Submitted Value')
                            ->columnSpanFull(),
                    ]),

                Section::make('Technical Metadata')
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('ip_address')
                                    ->label('IP Address')
                                    ->copyable(),

                                TextEntry::make('user_agent')
                                    ->label('Browser User Agent')
                                    ->wrap(),
                            ]),
                    ]),

            ]);
    }
}
