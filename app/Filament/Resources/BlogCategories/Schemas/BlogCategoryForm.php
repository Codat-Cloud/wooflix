<?php

namespace App\Filament\Resources\BlogCategories\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BlogCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Category Info')
                    ->schema([

                        Grid::make(2)->schema([
                            TextInput::make('name')
                                ->required()
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn($state, $set) => $set('slug', \Illuminate\Support\Str::slug($state))),
                            TextInput::make('slug')
                                ->required()
                                ->unique(ignoreRecord: true),
                        ]),
                        Textarea::make('description')->rows(3),
                        Toggle::make('is_active')->default(true),
                    ]),

                Section::make('Media & SEO')
                    ->schema([
                        FileUpload::make('image')
                            ->image()
                            ->disk('public')
                            ->directory('blog-categories')
                            ->helperText('Recommended: Upload a 500x500 JPG Image'),
                        TextInput::make('image_alt')
                            ->label('Image Alt Text')
                            ->placeholder('e.g., Cute puppy playing with cat toys')
                            ->helperText('Crucial for Google Image SEO and accessibility.'),
                    ]),
            ]);
    }
}
