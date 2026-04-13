<?php

namespace App\Filament\Resources\Coupons\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Laravel\Prompts\Grid;

class CouponForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([

                Section::make('Core Configuration')

                    ->description('Define the marketing title, code, and discount value.')
                    ->schema([
                        TextInput::make('title')
                            ->label('Custom Display Title')
                            ->placeholder('e.g., New User Discount')
                            ->helperText('Leave blank to auto-generate (e.g., 10% OFF)')
                            ->columnSpan(2),

                        TextInput::make('code')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->placeholder('SUMMER50')
                            ->columnSpan(1)
                            // 1. Force the data to be uppercase before saving to DB
                            ->dehydrateStateUsing(fn ($state) => strtoupper($state))
                            // 2. Make it look uppercase visually while typing (CSS)
                            ->extraInputAttributes(['style' => 'text-transform: uppercase']),

                        Select::make('type')
                            ->options([
                                'fixed' => 'Fixed Amount (₹)',
                                'percentage' => 'Percentage (%)',
                                'free_shipping' => 'Free Shipping',
                            ])
                            ->required()
                            ->native(false)
                            ->reactive()
                            ->columnSpan(1),

                        TextInput::make('value')
                            ->numeric()
                            ->label(fn($get) => $get('type') === 'percentage' ? 'Percentage' : 'Amount')
                            ->prefix(fn($get) => $get('type') === 'percentage' ? null : '₹')
                            ->suffix(fn($get) => $get('type') === 'percentage' ? '%' : null)
                            ->hidden(fn($get) => $get('type') === 'free_shipping')
                            ->required(fn($get) => $get('type') !== 'free_shipping')
                            ->columnSpan(1),

                        TextInput::make('description')
                            ->label('Sub-text')
                            ->placeholder('e.g., Use coupon on checkout')
                            ->columnSpan(1),
                    ])->columns(3)->columnSpan(3),

                // Restrictions
                Section::make('Restrictions & Limits')
                    ->description('Control how and when this coupon can be used.')
                    ->schema([
                        TextInput::make('min_spend')
                            ->label('Minimum Order Value')
                            ->numeric()
                            ->prefix('₹')
                            ->default(0),

                        TextInput::make('max_discount')
                            ->label('Max Discount Cap')
                            ->numeric()
                            ->prefix('₹')
                            ->helperText('Only applies to percentage discounts.'),

                        TextInput::make('usage_limit')
                            ->label('Total Usage Limit')
                            ->numeric()
                            ->helperText('Maximum number of times this coupon can be used across all users. Leave blank for infinite uses.')
                            ->placeholder('Unlimited'),

                        TextInput::make('user_limit')
                            ->label('Per User Limit')
                            ->numeric()
                            ->default(1),
                    ])->columns(2)->columnSpan(2),

                // Status & Badges
                Section::make('Status & Visibility')
                    ->description('Control frontend appearance.')
                    ->schema([
                        DateTimePicker::make('starts_at'),
                        DateTimePicker::make('expires_at'),

                        Toggle::make('is_best')
                            ->label('Highlight as "BEST" Offer')
                            ->onColor('warning'),

                        Toggle::make('is_visible')
                            ->label('Show in Public Offer List')
                            ->default(true),

                        Toggle::make('is_active')
                            ->label('Enable Coupon')
                            ->default(true),
                    ])->columnSpan(1),



            ]);
    }
}
