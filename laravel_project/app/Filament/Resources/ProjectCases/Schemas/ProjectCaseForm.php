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
                TextInput::make('meta_title')
                    ->label('SEO Заголовок')
                    ->placeholder('Введите SEO заголовок'),
                Textarea::make('meta_description')
                    ->label('SEO Описание')
                    ->placeholder('Введите SEO описание')
                    ->columnSpanFull(),
                TextInput::make('case_id')
                    ->required(),
                TextInput::make('title')
                    ->required(),
                TextInput::make('client')
                    ->required(),
                TextInput::make('period')
                    ->required(),
                FileUpload::make('image')
                    ->image()
                    ->disk('public')
                    ->directory('images')
                    ->visibility('public'),
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
                Textarea::make('description')
                    ->label('Описание проекта')
                    ->required()
                    ->rows(4)
                    ->columnSpanFull(),
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
                // === 🔗 ССЫЛКА НА УСЛУГУ ===
                TextInput::make('service_link_text')
                    ->label('Текст ссылки на услугу')
                    ->placeholder('Например: SEO продвижение')
                    ->helperText('Текст, который будет отображаться как ссылка на услугу под заголовком кейса'),
                TextInput::make('service_link_url')
                    ->label('URL ссылки на услугу')
                    ->placeholder('Например: /services/seo-prodvizhenie')
                    ->helperText('URL страницы услуги (можно использовать относительный путь)'),
                // === HTML ДОПОЛНИТЕЛЬНАЯ ИНФОРМАЦИЯ ===
                RichEditor::make('content')
                    ->label('Контент')
                    ->toolbarButtons([
                        // Группа 1: Форматирование текста
                        ['bold', 'italic', 'underline', 'strike', 'subscript', 'superscript', 'link'],
                        // Группа 2: Заголовки и выравнивание
                        ['h1', 'h2', 'h3', 'h4', 'alignStart', 'alignCenter', 'alignEnd', 'alignJustify'],
                        // Группа 3: Списки и цитаты
                        ['bulletList', 'orderedList', 'blockquote', 'codeBlock', 'code'],
                        // Группа 4: Вставка элементов
                        ['attachFiles', 'table', 'horizontalRule', 'details'],
                        // Группа 5: Цвет текста и очистка форматирования
                        ['textColor', 'highlight', 'clearFormatting'],
                        // Группа 6: Отмена/Повтор
                        ['undo', 'redo'],
                    ])
                    ->fileAttachmentsDisk('public')
                    ->fileAttachmentsDirectory('attachments')
                    ->fileAttachmentsVisibility('public')
                    ->resizableImages()  // <-- добавляем эту строку
                    ->columnSpanFull()
                    ->helperText('Редактируйте контент с помощью визуального редактора. HTML сохраняется автоматически.'),
                    Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                    TextInput::make('sort_order')
                    ->required()
                    ->numeric()
                    ->default(0),
                    Toggle::make('is_published')
                    ->columnSpan(2)
                    ->required(),
            ]);
    }
}
