<?php

namespace App\Filament\Resources\Products\Pages;

use App\Models\Product;
use Filament\Actions\Action;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ManageVariants extends Page
{
    protected static string $resource = \App\Filament\Resources\Products\ProductResource::class;

    protected string $view = 'filament.resources.products.pages.manage-variants';

    public Product $record;

    // ✅ REQUIRED
    public ?array $data = [];

    // public string $option_name;
    // public string $values;

    public function mount(Product $record): void
    {
        $this->record = $record;

        $inputs = session('variant_inputs_' . $record->id, []);

        $combinations = $this->generateCombinations($inputs);

        $this->form->fill([
            'variants' => collect($combinations)->map(function ($combo) {
                return [
                    'name' => implode(' / ', $combo),
                    'price' => 0,
                    'sale_price' => null,
                    'stock' => 0,
                    'sku' => '',
                    'is_active' => true,
                ];
            })->toArray(),
        ]);
    }

    // ================= ACTIONS =================

    private function generateCombinations($inputs)
    {
        $arrays = [];

        foreach ($inputs as $input) {
            $values = collect(explode(',', $input['values']))
                ->map(fn($v) => trim($v))
                ->filter()
                ->values()
                ->toArray();

            $arrays[] = $values;
        }

        $result = [[]];

        foreach ($arrays as $array) {
            $append = [];

            foreach ($result as $product) {
                foreach ($array as $item) {
                    $product[] = $item;
                    $append[] = $product;
                }
            }

            $result = $append;
        }

        return $result;
    }


    protected function getHeaderActions(): array
    {
        return [

            Action::make('generate')
                ->label('Generate Variants')
                ->action('generateVariants')
                ->color('primary'),

            Action::make('save')
                ->label('Save & Publish')
                ->action('saveVariants')
                ->color('success'),

        ];
    }

    public function generateVariants()
    {
        $data = $this->form->getState();

        // clear old
        $this->record->options()->delete();
        $this->record->variants()->delete();

        $options = [];

        foreach ($data['options'] as $opt) {

            $option = $this->record->options()->create([
                'name' => $opt['name'],
            ]);

            $values = collect(explode(',', $opt['values']))
                ->map(fn($v) => trim($v))
                ->filter();

            $valueModels = [];

            foreach ($values as $value) {
                $valueModels[] = $option->values()->create([
                    'value' => $value,
                ]);
            }

            $options[] = $valueModels;
        }

        // 🔥 GENERATE COMBINATIONS
        $combinations = $this->cartesian($options);

        foreach ($combinations as $combo) {

            $variant = $this->record->variants()->create([
                'sku' => strtoupper(Str::random(8)),
                'price' => 0,
                'stock' => 0,
            ]);

            $variant->optionValues()->attach(
                collect($combo)->pluck('id')
            );
        }
    }

    public function saveVariants()
    {
        $data = $this->form->getState();

        $this->record->variants()->delete();

        foreach ($data['variants'] as $variant) {

            $this->record->variants()->create([
                'name' => $variant['name'],
                'sku' => $variant['sku'] ?: strtoupper(Str::random(8)),
                'price' => $variant['price'],
                'sale_price' => $variant['sale_price'],
                'stock' => $variant['stock'],
                'is_active' => $variant['is_active'],
            ]);
        }

        $this->record->update([
            'is_active' => true,
        ]);

        session()->forget('variant_inputs_' . $this->record->id);

        return redirect()->route('filament.admin.resources.products.index');
    }

    private function cartesian($arrays)
    {
        $result = [[]];

        foreach ($arrays as $array) {
            $append = [];

            foreach ($result as $product) {
                foreach ($array as $item) {
                    $product[] = $item;
                    $append[] = $product;
                    array_pop($product);
                }
            }

            $result = $append;
        }

        return $result;
    }

    public function updateVariant($id, $field, $value)
    {
        $variant = $this->record->variants()->find($id);

        if ($variant) {
            $variant->update([$field => $value]);
        }
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([

            Section::make('Variant Setup')
                ->schema([

                    TextInput::make('option_name')
                        ->label('Variation Name')
                        ->placeholder('Flavor / Size / Pack')
                        ->required(),

                    Repeater::make('variants')
                        ->label('Variants')
                        ->schema([

                            TextInput::make('name')
                                ->disabled()
                                ->dehydrated(false),

                            TextInput::make('price')
                                ->numeric()
                                ->required(),

                            TextInput::make('sale_price')
                                ->numeric(),

                            TextInput::make('sku')
                                ->placeholder('Auto if empty'),

                            TextInput::make('stock')
                                ->numeric()
                                ->required(),

                            Toggle::make('is_active')
                                ->default(true),

                        ])
                        ->columns(6)
                        ->columnSpanFull()

                ])

        ])->statePath('data');
    }

    // public function form(Schema $schema): Schema
    // {
    //     return $schema
    //         ->components([

    //             // ================= OPTIONS =================
    //             Section::make('Product Options')
    //                 ->schema([

    //                     \Filament\Forms\Components\Repeater::make('options')
    //                         ->label('Options')
    //                         ->schema([

    //                             TextInput::make('name')
    //                                 ->label('Option Name')
    //                                 ->placeholder('Flavor / Size / Pack')
    //                                 ->required(),

    //                             TextInput::make('values')
    //                                 ->label('Values')
    //                                 ->placeholder('Comma separated (e.g. Chocolate, Vanilla)')
    //                                 ->required(),

    //                         ])
    //                         ->defaultItems(1)
    //                         ->columnSpanFull(),

    //                 ]),

    //                 Section::make('Variants')
    //                     ->schema([

    //                         Repeater::make('variants')
    //                             ->schema([

    //                                 TextInput::make('name')
    //                                     ->disabled(),

    //                                 TextInput::make('price')
    //                                     ->numeric(),

    //                                 TextInput::make('stock')
    //                                     ->numeric(),

    //                             ])
    //                             ->columnSpanFull()

    //                     ])

    //         ])
    //         ->statePath('data');
    // }
}
