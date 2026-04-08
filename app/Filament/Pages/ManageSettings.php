<?php

namespace App\Filament\Pages;

use App\Models\SiteSetting;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Cache;

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
        // Load all settings from DB into the form state
        $this->form->fill(
            SiteSetting::pluck('value', 'key')->toArray()
        );
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('save')
                ->label('Save Changes')
                ->submit('save') // This triggers the save() method in your class
                ->color('warning') // Match your orange branding
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
                        // TABS 1: IDENTITY
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
                        Tab::make('Footer')
                            ->icon('heroicon-m-queue-list')
                            ->schema([
                                RichEditor::make('footer_about')->columnSpanFull()
                                ->helperText('A short 2-3 sentence description of Wooflix to build brand trust at the bottom of every page.'),
                                TextInput::make('popular_searches')
                                    ->helperText('Separate keywords with commas.'),
                            ]),

                            // Inside the tabs array in ManageSettings.php
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
                                            ->helperText('Summary for Google search results. Best:2 lines give maximum results.'),
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
                    ]),
            ]);
    }

    // protected function getFormActions(): array
    // {
    //     return [
    //         Action::make('save')
    //             ->label('Save Settings')
    //             ->submit('save'),
    //     ];
    // }

    public function save(): void
    {
        $data = $this->form->getState();

        foreach ($data as $key => $value) {
            // Filament stores multiple uploads as arrays, we need the string
            $finalValue = is_array($value) ? array_first($value) : $value;
            SiteSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $finalValue]
            );
            
            // Clear cache for this specific key
            Cache::forget("setting.$key");
        }

        $this->mount();

        Notification::make()
            ->title('Settings saved successfully!')
            ->success()
            ->send();
    }
}
