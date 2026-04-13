<?php

namespace App\Filament\Resources\Reviews\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ReviewForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Product Review')
                    ->description('The text content of this review is read-only to preserve customer integrity.')
                    ->schema([

                        TextInput::make('product.name')
                            ->readOnly()
                            ->columnSpanFull()
                            ->label('Product'),

                        TextInput::make('customer_name')
                            ->readOnly()
                            ->required(),

                        TextInput::make('customer_email')
                            ->readOnly()
                            ->email()
                            ->required(),

                        TextInput::make('rating')
                            ->label('Star Rating')
                            ->suffix('Stars')
                            ->required()
                            ->numeric()
                            ->default(5),

                        Textarea::make('comment')
                            ->label('Customer Feedback')
                            ->required()
                            ->columnSpanFull(),

                        Toggle::make('is_approved')
                            ->helperText('Toggle this to approve the review for the frontend.')
                            ->onColor('success'),

                        Toggle::make('is_verified_buyer')
                            ->label('Verified Purchase'),

                    ])->columns(3),

                Section::make('Moderation & Photos')
                    ->schema([

                        Repeater::make('images')
                            ->relationship('images') // This links to your public function images() in the Review model
                            ->schema([
                                FileUpload::make('image_path') // This matches the 'image_path' column in review_images table
                                    ->label('Photo')
                                    ->image()
                                    ->disk('public')
                                    ->directory('reviews')
                                    ->imageEditor()
                                    // FEATURE: Disable uploading/replacing the file
                                    ->disabled() 
                                    ->deletable(false)
                                    ->required(),
                            ])
                            ->grid(2) // Shows previews in a nice grid instead of a long list
                            ->label('Review Photos')
                            // FEATURE: Prevent adding new images to the review
                            ->addable(false) 
                            // FEATURE: Ensure the admin can still delete the entire image record
                            ->deletable(true) 
                            ->reorderable(false)
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => 'Review Image')
                            ->columnSpan('full'),
                    ]),
            ]);
    }
}
