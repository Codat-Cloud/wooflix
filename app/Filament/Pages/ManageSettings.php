<?php

namespace App\Filament\Pages;

use App\Models\SiteSetting;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Filament\Forms\Get;

class ManageSettings extends Page
{
    use InteractsWithForms;

    protected string $view = 'filament.pages.manage-settings';

    protected static string|\UnitEnum|null $navigationGroup = 'Administration';

    protected static ?string $navigationLabel = 'App Settings';

    protected static ?int $navigationSort = 1;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedFingerPrint;

    public ?array $data = [];

    public function mount(): void
    {
        $settings = SiteSetting::pluck('value', 'key')->toArray();

        // 1. Decode Delivery Service Info
        if (! empty($settings['delivery_service_info']) && is_string($settings['delivery_service_info'])) {
            $decoded = json_decode($settings['delivery_service_info'], true);
            $settings['delivery_service_info'] = is_array($decoded) ? $decoded : [];
        }

        // 2. Decode Popular Searches (with backward compatibility)
        if (! empty($settings['popular_searches']) && is_string($settings['popular_searches'])) {
            $decoded = json_decode($settings['popular_searches'], true);

            if (is_array($decoded)) {
                $settings['popular_searches'] = $decoded;
            } else {
                // Convert old comma-separated string to repeater items automatically
                $settings['popular_searches'] = collect(explode(',', $settings['popular_searches']))
                    ->map(fn($k) => [
                        'keyword' => trim($k),
                        'url'     => '/shop?q=' . urlencode(trim($k)),
                    ])
                    ->filter(fn($item) => ! empty($item['keyword']))
                    ->values()
                    ->toArray();
            }
        }

        $this->form->fill($settings);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('save')
                ->label('Save Changes')
                ->color('warning')
                ->formId('form')
                ->submit('save'),
        ];
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->schema([
                Tabs::make('Settings')
                    ->persistTabInQueryString()
                    ->tabs([
                        // TAB 1: IDENTITY
                        Tab::make('Identity')
                            ->icon('heroicon-m-finger-print')
                            ->schema([
                                Grid::make(2)->schema([
                                    FileUpload::make('logo_desktop')->image()->disk('public')->directory('settings')
                                        ->helperText('Transparent PNG recommended. Ideal size: 250x60px. Appears in the main website header.'),
                                    FileUpload::make('logo_mobile')->image()->disk('public')->directory('settings')
                                        ->helperText('Transparent PNG recommended. Ideal size: 150x40px. Optimized for smaller screens and sticky headers.'),
                                    FileUpload::make('favicon')->image()->disk('public')->directory('settings')
                                        ->helperText('Must be a square (1:1 ratio). Upload a high-res PNG (512x512px). This appears in browser tabs and Google search results.'),
                                ]),
                            ]),

                        // TAB 2: CONTACT & SOCIAL
                        Tab::make('Contact & Social')
                            ->icon('heroicon-m-chat-bubble-left-right')
                            ->schema([
                                Grid::make(2)->schema([
                                    TextInput::make('contact_email')->email()
                                        ->helperText('Primary email for customer inquiries. Used in the footer and contact page.'),
                                    TextInput::make('contact_phone')
                                        ->helperText('Official support number. Use international format (e.g., +91 85879 06587) for better mobile click-to-call.'),
                                ]),
                                Textarea::make('office_address')->rows(3)
                                    ->helperText('Physical location or Registered Office address.'),
                                Grid::make(2)->schema([
                                    TextInput::make('facebook_url')->url()->placeholder('https://facebook.com/...'),
                                    TextInput::make('instagram_url')->url()->placeholder('https://instagram.com/...'),
                                    TextInput::make('linkedin_url')->url()->placeholder('https://linkedin.com/...'),
                                    TextInput::make('pinterest_url')
                                        ->url()
                                        ->label('Pinterest URL')
                                        ->placeholder('https://pinterest.com/wooflix'),
                                    TextInput::make('youtube_url')
                                        ->url()
                                        ->label('YouTube Channel')
                                        ->placeholder('https://youtube.com/@wooflix'),
                                ]),
                            ]),

                        // TAB 3: TRACKING & SCRIPTS
                        Tab::make('Tracking Scripts')
                            ->icon('heroicon-m-code-bracket')
                            ->schema([
                                Section::make('Global Analytics')
                                    ->description('Paste your full script tags here (Google Analytics, FB Pixel, etc.)')
                                    ->schema([
                                        Textarea::make('header_scripts')
                                            ->label('Header Scripts (Inside <head>)')
                                            ->rows(5)
                                            ->helperText('Paste complete code blocks here (including <script> tags). Useful for Google Analytics, FB Pixel, and Verify Meta tags.'),
                                        Textarea::make('footer_scripts')
                                            ->label('Footer Scripts (Before </body>)')
                                            ->rows(5)
                                            ->helperText('Use this for live chat widgets or non-critical tracking scripts that should load after the page content.'),
                                    ]),
                            ]),

                        // TAB 4: FOOTER CONTENT
                        Tab::make('Header & Footer')
                            ->icon('heroicon-m-queue-list')
                            ->schema([
                                TextInput::make('top_bar')
                                    ->helperText('A short single line for top bar.'),

                                RichEditor::make('footer_about')->columnSpanFull()
                                    ->helperText('A short 2-3 sentence description of Wooflix to build brand trust at the bottom of every page.'),
                                Repeater::make('popular_searches')
                                    ->label('Popular Search Keywords')
                                    ->schema([
                                        TextInput::make('keyword')
                                            ->label('Keyword / Phrase')
                                            ->placeholder('e.g. Royal Canin Dog Food')
                                            ->required(),

                                        TextInput::make('url')
                                            ->label('Target URL')
                                            ->placeholder('e.g. /shop?q=royal+canin or /collection/dog-food')
                                            ->required(),
                                    ])
                                    ->columns(2)
                                    ->reorderable()
                                    ->collapsible()
                                    ->defaultItems(3)
                                    ->itemLabel(fn(array $state): ?string => $state['keyword'] ?? 'Search Keyword')
                                    ->columnSpanFull()
                                    ->helperText('Add keywords and the destination search or collection page to open when clicked.'),
                            ]),

                        // TAB 5: GLOBAL SEO
                        Tab::make('Global SEO')
                            ->icon('heroicon-m-globe-alt')
                            ->schema([
                                Section::make('Homepage & Global Meta')
                                    ->description('These tags are used for the homepage and as fallbacks for other pages.')
                                    ->schema([
                                        TextInput::make('site_name')
                                            ->label('Website Name')
                                            ->placeholder('e.g., Wooflix'),
                                        TextInput::make('site_title')
                                            ->label('Homepage Title Tag')
                                            ->placeholder('Welcome to my website | Buy Products')
                                            ->helperText('Appears in browser tab. Best: 50-60 characters.'),
                                        Textarea::make('site_description')
                                            ->label('Meta Description')
                                            ->rows(2)
                                            ->helperText('Summary for Google search results. Best: 2 lines give maximum results.'),
                                        TextInput::make('site_keywords')
                                            ->label('Global Keywords')
                                            ->placeholder('pets, dog food, cat toys, india'),
                                        FileUpload::make('og_image_default')
                                            ->label('Default Share Image (OpenGraph)')
                                            ->image()
                                            ->disk('public')
                                            ->directory('settings')
                                            ->helperText('Image shown when sharing the website on WhatsApp/Facebook and X. Best: Image with .jpg and size 1200x630px'),
                                    ]),
                            ]),

                        // TAB 6: SMTP
                        Tab::make('SMTP')
                            ->icon('heroicon-m-envelope')
                            ->schema([
                                Section::make('SMTP Configuration')
                                    ->description('Configure outgoing email settings for order emails, OTPs, notifications, and contact forms.')
                                    ->schema([
                                        Grid::make(2)->schema([
                                            TextInput::make('smtp_host')
                                                ->label('SMTP Host')
                                                ->placeholder('smtp.gmail.com')
                                                ->helperText('Mail server hostname provided by your email provider.'),

                                            TextInput::make('smtp_from_address')
                                                ->label('From Email Address')
                                                ->email()
                                                ->placeholder('noreply@yourdomain.com'),

                                            TextInput::make('smtp_username')
                                                ->label('SMTP Username')
                                                ->placeholder('support@yourdomain.com')
                                                ->helperText('Usually your full email address.'),

                                            TextInput::make('smtp_password')
                                                ->label('SMTP Password')
                                                ->password()
                                                ->revealable()
                                                ->helperText('Stored securely in database.'),

                                            TextInput::make('smtp_encryption')
                                                ->label('Encryption')
                                                ->placeholder('tls')
                                                ->helperText('Use tls or ssl.'),

                                            TextInput::make('smtp_port')
                                                ->label('SMTP Port')
                                                ->numeric()
                                                ->placeholder('587')
                                                ->helperText('Usually 587 for TLS or 465 for SSL.'),

                                            TextInput::make('smtp_from_name')
                                                ->label('From Name')
                                                ->placeholder('Wooflix'),
                                        ]),
                                    ]),
                            ]),

                        // TAB 7: DELIVERY & SERVICE INFORMATION
                        Tab::make('Delivery & Service Information')
                            ->icon('heroicon-m-truck') // 🟢 Fixed valid Heroicon
                            ->schema([
                                Section::make('Delivery & Service Information')
                                    ->description('Manage the bullet points displayed on the single product page.')
                                    ->schema([
                                        Repeater::make('delivery_service_info')
                                            ->label('Delivery & Service Points')
                                            ->schema([
                                                Grid::make(3)->schema([
                                                    // 1. Icon Selection
                                                    Select::make('icon')
                                                        ->label('Icon / Badge')
                                                        ->options([
                                                            'lightning' => '⚡ Lightning Bolt (Availability)',
                                                            'truck'     => '🚚 Delivery Truck (Shipping)',
                                                            'package'   => '📦 Package Box (Returns)',
                                                            'free'      => '🆓 "FREE" Badge',
                                                            'cod'       => '💵 "COD" Cash on Delivery',
                                                            'shield'    => '🛡️ Shield (100% Genuine / Verified)',
                                                            'paw'       => '🐾 Paw Print (Pet Safe)',
                                                            'support'   => '🎧 Headset (Expert Support)',
                                                            'clock'     => '⏱️ Clock (Fast / Same-Day Dispatch)',
                                                            'star'      => '⭐ Star (Top Rated)',
                                                            'custom'    => '✏️ Custom Emoji or Short Text',
                                                        ])
                                                        ->default('lightning')
                                                        ->live()
                                                        ->required(),

                                                    // Custom text/emoji input (only shown if "custom" is selected)
                                                    TextInput::make('custom_icon')
                                                        ->label('Custom Icon / Text')
                                                        ->placeholder('e.g. 🐶 or 100%')
                                                        ->maxLength(6)
                                                        ->visible(fn($get): bool => $get('icon') === 'custom')
                                                        ->required(fn($get): bool => $get('icon') === 'custom'),

                                                    // Dynamic Livewire Connection
                                                    Select::make('dynamic_type')
                                                        ->label('Item Behavior')
                                                        ->options([
                                                            'static'               => 'Standard Text (Static Highlight)',
                                                            'express_availability' => 'Dynamic: Express Availability Check',
                                                            'delivery_date'        => 'Dynamic: Estimated Delivery Date',
                                                        ])
                                                        ->default('static')
                                                        ->helperText('Select a dynamic option if this item should update when entering a pincode.')
                                                        ->columnSpan(fn($get): int => $get('icon') === 'custom' ? 1 : 2),
                                                ]),

                                                Grid::make(2)->schema([
                                                    TextInput::make('text')
                                                        ->label('Primary Text')
                                                        ->placeholder('e.g. Check delivery availability or No Exchange & Returns')
                                                        ->required(),

                                                    TextInput::make('highlight')
                                                        ->label('Highlighted Text (Bold)')
                                                        ->placeholder('e.g. ₹699 (optional)'),
                                                ]),
                                            ])
                                            ->reorderable()
                                            ->collapsible()
                                            ->itemLabel(fn(array $state): ?string => ($state['text'] ?? 'Service Point') . (!empty($state['highlight']) ? ' ' . $state['highlight'] : ''))
                                            ->columnSpanFull()
                                    ]),
                            ]),
                    ]),
            ]);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        foreach ($data as $key => $value) {
            // Encode repeaters to JSON
            if (in_array($key, ['delivery_service_info', 'popular_searches'])) {
                $finalValue = is_array($value) ? json_encode(array_values($value)) : $value;
            } elseif (is_array($value)) {
                $finalValue = Arr::first($value);
            } else {
                $finalValue = $value;
            }

            SiteSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $finalValue]
            );

            Cache::forget("setting.$key");
        }

        Cache::forget('site_settings_all');

        $this->mount();

        Notification::make()
            ->title('Settings saved successfully!')
            ->success()
            ->send();
    }
}
