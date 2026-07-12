<?php

namespace App\Filament\Resources\ProjectCases\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Repeater;
use Illuminate\Support\HtmlString;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ProjectCaseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('case_id')
                    ->required(),
                TextInput::make('title')
                    ->required(),
                TextInput::make('client')
                    ->required(),
                Select::make('industry_category_id')
                    ->relationship('industryCategory', 'name', function ($query) {
                        $query->where('is_active', true);
                    })
                    ->required()
                    ->searchable()
                    ->preload()
                    ->options(function () {
                        return \App\Models\IndustryCategory::active()
                            ->get()
                            ->mapWithKeys(fn ($category) => [
                                $category->id => $category->name
                            ]);
                    }),
                TextInput::make('period')
                    ->required(),
                // Минимальный тестовый пример для отладки
                FileUpload::make('image')
                    ->image()
                    ->disk('public')
                    ->directory('images')
                    ->visibility('public'),
                Textarea::make('description')
                    ->label('Описание проекта')
                    ->required()
                    ->rows(4)
                    ->columnSpanFull(),
                // === 📊 МЕТРИКИ ДО/ПОСЛЕ ===
                Repeater::make('metrics')
                    ->label('Метрики "До и После"')
                    ->schema([
                        TextInput::make('name')
                            ->label('Название метрики')
                            ->placeholder('Например: Трафик, Конверсия, Лиды')
                            ->required(),
                        TextInput::make('before')
                            ->label('До')
                            ->placeholder('Значение до'),
                        TextInput::make('after')
                            ->label('После')
                            ->placeholder('Значение после'),
                    ])
                    ->addActionLabel('Добавить метрику')
                    ->reorderableWithButtons()
                    ->collapsible()
                    ->columnSpanFull()
                    ->grid(3),
                Toggle::make('is_published')
                    ->required(),
                TextInput::make('sort_order')
                    ->required()
                    ->numeric()
                    ->default(0),

                // === 🔗 ССЫЛКА НА УСЛУГУ ===
                TextInput::make('service_link_text')
                    ->label('Текст ссылки на услугу')
                    ->placeholder('Например: SEO продвижение')
                    ->helperText('Текст, который будет отображаться как ссылка на услугу под заголовком кейса'),

                TextInput::make('service_link_url')
                    ->label('URL ссылки на услугу')
                    ->placeholder('Например: /services/seo-prodvizhenie')
                    ->helperText('URL страницы услуги (можно использовать относительный путь)'),

                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),

                // === 🎯 РЕЗУЛЬТАТЫ ПРОЕКТА ===
                Repeater::make('results')
                    ->label('Результаты проекта')
                    ->simple(
                        TextInput::make('result')
                            ->placeholder('Результат проекта')
                            ->required()
                    )
                    ->addActionLabel('Добавить результат')
                    ->reorderableWithButtons()
                    ->collapsible()
                    ->columnSpanFull()
                    ->grid(2),
                // === HTML ДОПОЛНИТЕЛЬНАЯ ИНФОРМАЦИЯ ===
                Textarea::make('content')
                    ->label('Дополнительная информация (HTML)')
                    ->rows(6)
                    ->live(onBlur: true)
                    ->helperText('Вводите HTML-код. Используйте теги: <p>, <h2>, <h3>, <ul>, <li>, <strong>, <em>, <a>')
                    ->columnSpanFull(),

                Placeholder::make('content_preview')
                    ->label('Предпросмотр дополнительной информации')
                    ->content(function ($get) {
                        $content = $get('content');
                        if (!$content) {
                            return new HtmlString('<div class="text-gray-500 italic p-4 bg-gray-50 rounded-lg">Введите дополнительную информацию выше для предпросмотра</div>');
                        }

                        return new HtmlString(
                            '<div class="p-4 bg-white border rounded-lg shadow-sm" style="max-height: 250px; overflow-y: auto;">' .
                            '<div style="color: #4b5563; line-height: 1.6;">' .
                            '<style scoped>' .
                            '.preview-content h1 { font-size: 2rem; font-weight: bold; color: #1f2937; margin: 1.5rem 0 1rem 0; }' .
                            '.preview-content h2 { font-size: 1.75rem; font-weight: bold; color: #1f2937; margin: 1.25rem 0 0.75rem 0; }' .
                            '.preview-content h3 { font-size: 1.5rem; font-weight: 600; color: #1f2937; margin: 1rem 0 0.5rem 0; }' .
                            '.preview-content p { margin-bottom: 1rem; color: #4b5563; line-height: 1.6; }' .
                            '.preview-content ul { list-style-type: disc; list-style-position: inside; margin-bottom: 1rem; }' .
                            '.preview-content ol { list-style-type: decimal; list-style-position: inside; margin-bottom: 1rem; }' .
                            '.preview-content li { margin-bottom: 0.5rem; color: #4b5563; }' .
                            '.preview-content strong { font-weight: 600; color: #1f2937; }' .
                            '.preview-content em { font-style: italic; }' .
                            '.preview-content a { color: #0891b2; text-decoration: underline; }' .
                            '</style>' .
                            '<div class="preview-content">' . $content . '</div>' .
                            '</div>' .
                            '</div>'
                        );
                    })
                    ->hidden(fn ($get) => empty($get('content')))
                    ->columnSpanFull(),
                TextInput::make('meta_title')
                    ->label('SEO Заголовок')
                    ->placeholder('Введите SEO заголовок'),
                Textarea::make('meta_description')
                    ->label('SEO Описание')
                    ->placeholder('Введите SEO описание')
                    ->columnSpanFull(),
            ]);
    }
}
