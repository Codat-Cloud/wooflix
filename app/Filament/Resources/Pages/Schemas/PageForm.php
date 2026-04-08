<?php

namespace App\Filament\Resources\Pages\Schemas;

use App\Models\Page;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Page Details')
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            // live(onBlur: true) triggers the update once the user clicks away from the field
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (string $operation, $state, $set) {
                                // We only auto-generate the slug during the 'create' operation
                                // This prevents the slug from changing on old pages (which breaks SEO links)
                                if ($operation === 'create') {
                                    $set('slug', Str::slug($state));
                                }
                            })
                            ->columnSpanFull(),

                        TextInput::make('slug')
                            ->required()
                            // Dehydrated ensures the value is sent to the database even if the field is manipulated
                            ->dehydrated()
                            ->unique(Page::class, 'slug', ignoreRecord: true)
                            // This allows the user to manually override the auto-generated slug
                            ->helperText('URL-friendly version of the title. Usually generated automatically.')
                            ->columnSpanFull(),

                        RichEditor::make('content')
                            ->columnSpanFull(),

                        Toggle::make('is_active')
                            ->default(true)
                            ->required(),
                    ]),

                Section::make('SEO')
                    ->schema([

                        TextInput::make('seo_title'),
                        Textarea::make('seo_description')
                            ->columnSpanFull(),
                    ])

            ]);
    }
}
