<?php

namespace App\Filament\Resources\Blogs\Schemas;

use App\Models\Blog;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class BlogForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Blog Post')
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (string $operation, $state, $set, $get) {
                                // Only auto-generate the slug if the slug field is currently empty 
                                // OR if we are in the 'create' operation and the user hasn't 
                                // manually changed the slug yet.
                                if ($operation === 'create' && filled($state)) {
                                    $set('slug', Str::slug($state));
                                }
                            })
                            ->columnSpanFull(),

                        TextInput::make('slug')
                            ->required()
                            // 'dehydrated' ensures the value is sent to the database even if it's auto-filled
                            ->dehydrated() 
                            // This handles the "Check for slug unique" before saving
                            ->unique(Blog::class, 'slug', ignoreRecord: true)
                            ->helperText('You can manually edit this URL. Changing it after a post is live may break old links.')
                            ->columnSpanFull(),

                        // Multiple Category Selection
                        Select::make('categories')
                            ->multiple()
                            ->relationship('categories', 'name')
                            ->required()
                            ->preload(),

                        FileUpload::make('featured_image')
                            ->image()
                            ->disk('public')
                            ->directory('blogs')
                            ->required()
                            ->helperText('Recomended 1200x630px. Upload JPG. We will automatically generate a WebP version for speed.'),

                        RichEditor::make('content'),
                        
                        Toggle::make('is_published')
                            ->default(true),
                    ]),

                Section::make('SEO Tags')
                    ->schema([
                        TextInput::make('seo_title')
                            ->label('SEO Title'),
                        Textarea::make('seo_description')
                            ->label('SEO Description'),
                        TextInput::make('image_alt')
                            ->label('Featured Image ALT Tag'),
                    ]),

                Section::make('Interlinking')
                    ->description('Boost SEO by linking to other relevant articles.')
                    ->schema([
                        Select::make('related_posts')
                            ->multiple()
                            ->searchable()
                            ->options(Blog::all()->pluck('title', 'id'))
                            ->helperText('This will show as "Read Also" or "Related Articles".'),
                    ]),
            ]);
    }
}
