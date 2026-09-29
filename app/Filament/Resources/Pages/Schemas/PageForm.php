<?php

namespace App\Filament\Resources\Pages\Schemas;

use App\Models\Page;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;
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
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (string $operation, $state, $set) {
                                if ($operation === 'create') {
                                    $set('slug', Str::slug($state));
                                }
                            })
                            ->columnSpanFull(),

                        TextInput::make('slug')
                            ->required()
                            ->dehydrated()
                            ->unique(Page::class, 'slug', ignoreRecord: true)
                            ->helperText('URL-friendly version of the title. Usually generated automatically.')
                            ->columnSpanFull(),

                        // 🟢 Switch between Standard Rich Text and Visual Drag & Drop
                        Select::make('editor_type')
                            ->label('Page Editor Mode')
                            ->options([
                                'simple'   => 'Standard Rich Text (CRUD)',
                                'grapesjs' => 'Visual Drag & Drop (GrapesJS)',
                            ])
                            ->default('simple')
                            ->required()
                            ->live()
                            ->columnSpanFull(),

                        // 🟢 1. Standard Content Editor (Visible only when 'simple')
                        RichEditor::make('content')
                            ->label('Page Content')
                            ->visible(fn($get) => $get('editor_type') === 'simple')
                            ->columnSpanFull(),

                        // 🟢 2. Visual Builder Launcher (Visible only when 'grapesjs')
                        Placeholder::make('grapesjs_launcher')
                            ->label('Visual Builder')
                            ->visible(fn($get) => $get('editor_type') === 'grapesjs')
                            ->content(function (?Page $record) {
                                if (! $record || ! $record->exists) {
                                    return new HtmlString('
                                        <div class="p-3 bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-800 rounded-lg text-sm text-amber-800 dark:text-amber-200">
                                            ⚠️ Please save/create this page first. Once saved, you can launch the GrapesJS visual editor.
                                        </div>
                                    ');
                                }

                                $builderUrl = route('admin.pages.builder', $record->id);

                                return new HtmlString("
                                    <div class='flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-lg'>
                                        <div>
                                            <p class='font-medium text-sm text-gray-900 dark:text-white'>GrapesJS Canvas Mode Active</p>
                                            <p class='text-xs text-gray-500 dark:text-gray-400'>Design blocks, styling, and layouts in fullscreen.</p>
                                        </div>
                                        <a href='{$builderUrl}' target='_blank' 
                                           style='background-color: #ff6b00; color: #fff; padding: 8px 16px; border-radius: 6px; font-weight: 600; text-decoration: none; font-size: 0.875rem;'>
                                            Launch Visual Builder ↗
                                        </a>
                                    </div>
                                ");
                            })
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
                    ]),
            ]);
    }
}
