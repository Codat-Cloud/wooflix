<?php

namespace App\Filament\Resources\FormSubmissions\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class FormSubmissionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('form_type')
                    ->required()
                    ->default('contact'),
                TextInput::make('page_id')
                    ->numeric(),
                TextInput::make('data')
                    ->required(),
                TextInput::make('ip_address'),
                Textarea::make('user_agent')
                    ->columnSpanFull(),
                Toggle::make('is_read')
                    ->required(),
            ]);
    }
}
