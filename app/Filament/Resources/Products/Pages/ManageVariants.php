<?php

namespace App\Filament\Resources\Products\Pages;

use App\Models\Product;
use App\Models\ProductOption;
use App\Models\ProductOptionValue;
use App\Models\ProductVariant;
use Filament\Actions\Action;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ManageVariants extends Page
{
    protected static string $resource = \App\Filament\Resources\Products\ProductResource::class;

    // Filament will look for: resources/views/filament/resources/products/pages/manage-variants.blade.php
    // But we override with the default page view so no custom blade is needed:
    protected string $view = 'filament.resources.products.pages.manage-variants';

    public Product $record;

    public ?array $data = [];

    // =========================================================
    // MOUNT — pre-fill from DB if options/variants already exist
    // =========================================================

    public function mount(Product $record): void
    {
        $this->record = $record;

        // 1. Pre-fill options (This part is usually fine)
        $options = $record->options()->with('values')->orderBy('position')->get();

        if ($options->isNotEmpty()) {
            $this->data['options'] = $options->map(fn($opt) => [
                'name'   => $opt->name,
                'values' => $opt->values->pluck('value')->join(', '),
            ])->toArray();
        } else {
            $this->data['options'] = [['name' => '', 'values' => '']];
        }

        // 2. Pre-fill variants (FIXED HERE)
        $variants = $record->variants()->get();

        if ($variants->isNotEmpty()) {
            $this->data['variants'] = $record->variants->map(fn($v) => [
                'id'         => $v->id,
                'name'       => $v->name,
                'sku'        => $v->sku,
                'price'      => $v->price,
                'sale_price' => $v->sale_price,
                'stock'      => $v->stock,
                'is_active'  => (bool) $v->is_active,
            ])->toArray();
        } else {
            $this->data['variants'] = [];
        }

        // dd($this->data['variants']);
    }

    // =========================================================
    // FORM
    // =========================================================

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([

                // ── SECTION 1: Options ──────────────────────────────
                Section::make('Step 1 — Define Options')
                    ->description('Add attributes like Color, Size, Flavor. Use comma-separated values.')
                    ->schema([
                        Repeater::make('options')
                            ->schema([
                                TextInput::make('name')
                                    ->label('Option Name')
                                    ->placeholder('e.g. Color, Size, Flavor')
                                    ->required()
                                    ->columnSpan(1),

                                TextInput::make('values')
                                    ->label('Values')
                                    ->placeholder('e.g. Red, Blue, Green')
                                    ->helperText('Comma separated')
                                    ->required()
                                    ->columnSpan(2),
                            ])
                            ->columns(3)
                            ->addActionLabel('+ Add Option')
                            ->defaultItems(1)
                            ->reorderable()
                            ->collapsible()
                            ->itemLabel(fn(array $state): ?string => $state['name'] ?? null),
                    ]),

                // ── SECTION 2: Variants (filled by Generate action) ─
                Section::make('Step 2 — Edit Variants')
                    ->description('Click "Generate Matrix" above first, then fill in SKU, price, and stock for each variant.')
                    ->schema([
                        Repeater::make('variants')
                            ->schema([
                                TextInput::make('name')
                                    ->label('Variant')
                                    ->disabled()
                                    ->columnSpan(2),

                                TextInput::make('sku')
                                    ->label('SKU')
                                    ->required()
                                    ->columnSpan(1),

                                TextInput::make('price')
                                    ->label('Price')
                                    ->numeric()
                                    ->prefix('₹')
                                    ->required()
                                    ->columnSpan(1),

                                TextInput::make('sale_price')
                                    ->label('Sale Price')
                                    ->numeric()
                                    ->prefix('₹')
                                    ->columnSpan(1),

                                TextInput::make('stock')
                                    ->label('Stock')
                                    ->numeric()
                                    ->default(0)
                                    ->columnSpan(1),

                                Toggle::make('is_active')
                                    ->label('Active')
                                    ->default(true)
                                    ->columnSpan(1),
                            ])
                            ->columns(4)
                            ->addable(false)       // only via Generate button
                            ->deletable(true)
                            ->reorderable(false)
                            ->collapsible()
                            ->itemLabel(fn(array $state): ?string => $state['name'] ?? null),
                    ]),

            ])
            ->statePath('data');
    }

    // =========================================================
    // HEADER ACTIONS
    // =========================================================

    protected function getHeaderActions(): array
    {
        return [

            // ── Generate Matrix ─────────────────────────────────────
            Action::make('generate')
                ->label('⚡ Generate Matrix')
                ->color('warning')
                ->requiresConfirmation()
                ->modalHeading('Regenerate Variants?')
                ->modalDescription('This will replace the variants list below with a fresh matrix based on your options. SKU/price data already entered will be preserved where variant names match.')
                ->modalSubmitActionLabel('Yes, Generate')
                ->action(function () {

                    $options = collect($this->data['options'] ?? []);

                    // Validate: every option must have a name and at least one value
                    foreach ($options as $opt) {
                        if (empty(trim($opt['name'] ?? '')) || empty(trim($opt['values'] ?? ''))) {
                            Notification::make()
                                ->title('Each option must have a name and at least one value.')
                                ->warning()
                                ->send();
                            return;
                        }
                    }

                    // Build groups: [['Red','Blue'], ['S','M','L'], ...]
                    $groups = $options->map(
                        fn($opt) =>
                        collect(explode(',', $opt['values']))
                            ->map(fn($v) => trim($v))
                            ->filter()
                            ->values()
                            ->toArray()
                    )->filter(fn($g) => count($g) > 0)->values()->toArray();

                    if (empty($groups)) {
                        Notification::make()
                            ->title('No valid option values found.')
                            ->warning()
                            ->send();
                        return;
                    }

                    $combinations = $this->cartesianProduct($groups);

                    // Preserve any existing data keyed by variant name
                    $existing = collect($this->data['variants'] ?? [])->keyBy('name');

                    $newVariants = [];
                    foreach ($combinations as $combo) {
                        $name = implode(' / ', $combo);
                        $newVariants[] = array_merge(
                            [
                                'id'         => null,
                                'name'       => $name,
                                'sku'        => strtoupper(Str::random(8)),
                                'price'      => '',
                                'sale_price' => null,
                                'stock'      => 0,
                                'is_active'  => true,
                            ],
                            // Overwrite defaults with any previously entered data
                            collect($existing->get($name, []))->except('id')->toArray()
                        );
                    }

                    $this->data['variants'] = $newVariants;

                    Notification::make()
                        ->title(count($newVariants) . ' variants generated!')
                        ->success()
                        ->send();
                }),

            // ── Save & Publish ───────────────────────────────────────
            Action::make('save')
                ->label('Save & Publish')
                ->color('success')
                ->action('saveVariants'),

            // ── Save as Draft ────────────────────────────────────────
            Action::make('draft')
                ->label('Save as Draft')
                ->color('gray')
                ->action('saveAsDraft'),

        ];
    }

    // =========================================================
    // SAVE LOGIC
    // =========================================================

    public function saveVariants(): void
    {
        $this->save(publish: true);
    }

    public function saveAsDraft(): void
    {
        $this->save(publish: false);
    }

    private function save(bool $publish): void
    {
        // CRITICAL: This syncs the UI inputs with the Livewire state and validates the form
        $data = $this->form->getState();

        // 1. Persist options + their values
        $this->record->options()->delete(); // Cascade deletes old option values

        $optionValueLookup = [];
        $optionPosition = 0;

        foreach ($data['options'] as $optionData) {
            $name = trim($optionData['name'] ?? '');
            if (empty($name)) continue;

            /** @var ProductOption $option */
            $option = $this->record->options()->create([
                'name'     => $name,
                'position' => $optionPosition++,
            ]);

            $values = collect(explode(',', $optionData['values'] ?? ''))
                ->map(fn($v) => trim($v))
                ->filter();

            $valuePosition = 0;
            foreach ($values as $val) {
                /** @var ProductOptionValue $ov */
                $ov = $option->values()->create([
                    'value'    => $val,
                    'position' => $valuePosition++,
                ]);
                // Store by value for easy lookup during variant linking
                $optionValueLookup[$val] = $ov;
            }
        }

        // 2. Persist variants
        $this->record->variants()->delete();

        foreach ($data['variants'] as $variantData) {
            $variantName = trim($variantData['name'] ?? '');
            if (empty($variantName)) continue;

            /** @var ProductVariant $variant */
            $variant = $this->record->variants()->create([
                'name'       => $variantName,
                'sku'        => $variantData['sku'] ?? strtoupper(Str::random(8)),
                'price'      => $variantData['price'] ?? 0,
                'sale_price' => $variantData['sale_price'] ?: null,
                'stock'      => $variantData['stock'] ?? 0,
                'is_active'  => $variantData['is_active'] ?? true,
            ]);

            // 3. Link variant to option values (Pivot Table)
            // We split by " / " (with spaces) to match your generation logic
            $parts = collect(explode(' / ', $variantName))->map(fn($p) => trim($p));

            $valueIds = $parts
                ->map(fn($part) => $optionValueLookup[$part]?->id ?? null)
                ->filter()
                ->toArray();

            if (!empty($valueIds)) {
                $variant->optionValues()->sync($valueIds);
            }
        }

        // 4. Update parent product status
        $this->record->update(['is_active' => $publish]);

        Notification::make()
            ->title($publish ? 'Product published!' : 'Saved as draft.')
            ->success()
            ->send();

        // Redirect using Filament's helper to ensure the notification persists
        $this->redirect($this->getResource()::getUrl('index'));
    }

    // =========================================================
    // HELPERS
    // =========================================================

    private function cartesianProduct(array $arrays): array
    {
        $result = [[]];
        foreach ($arrays as $values) {
            $temp = [];
            foreach ($result as $existing) {
                foreach ($values as $value) {
                    $temp[] = array_merge($existing, [$value]);
                }
            }
            $result = $temp;
        }
        return $result;
    }
}
