<?php

namespace App\Filament\Resources\ProductFilterTags\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ProductFilterTagForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Filter tag')
                    ->schema([
                        Select::make('type')
                            ->options([
                                'pet_type' => 'Pet Type',
                                'life_stage' => 'Life Stage',
                                'breed_size' => 'Dog Breed Size',
                                'color' => 'Color',
                                'pattern' => 'Pattern',
                                'size' => 'Size',
                            ])
                            ->required(),

                        TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(
                                fn($state, $set) =>
                                $set('slug', Str::slug($state))
                            ),

                        TextInput::make('slug')
                            ->required()
                            ->dehydrated()
                            ->unique(
                                ignoreRecord: true,
                                modifyRuleUsing: fn($rule, callable $get) =>
                                $rule->where('type', $get('type'))
                            ),


                        Toggle::make('is_active')
                            ->default(true),

                        TextInput::make('sort_order')
                            ->numeric()
                            ->default(0),
                    ])
            ]);
    }
}
