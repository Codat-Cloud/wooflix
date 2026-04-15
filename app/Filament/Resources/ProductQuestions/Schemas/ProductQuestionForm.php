<?php

namespace App\Filament\Resources\ProductQuestions\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProductQuestionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Product Question')
                    ->description('The text content of this question is read-only to preserve customer integrity.')
                    ->schema([

                        TextInput::make('product_name')
                            ->readOnly()
                            ->columnSpanFull()
                            ->formatStateUsing(fn ($record) => $record->product->name ?? 'N/A')
                            ->label('Product Name'),

                        TextInput::make('email')
                            ->label('Customer Email')
                            ->readOnly()
                            ->email()
                            ->required(),

                        TextInput::make('name')
                            ->label('Customer Name')
                            ->readOnly()
                            ->required(),

                        Textarea::make('question')
                            ->label('Customer Asked')
                            ->readOnly()
                            ->rows(3)
                            ->required(),

                        Textarea::make('answer')
                            ->label('Your Reply')
                            ->rows(3)
                            ->required(),

                        Toggle::make('is_visible'),
                            
                    ])
            ]);
    }
}
