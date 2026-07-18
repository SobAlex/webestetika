<?php

namespace App\Filament\Resources\Services\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Placeholder;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;

class ServiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (string $operation, $state, callable $set) {
                        if ($operation !== 'create') {
                            return;
                        }
                        $set('slug', \Illuminate\Support\Str::slug($state));
                    }),
                TextInput::make('slug')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (callable $set, $state) => $set('slug', \Illuminate\Support\Str::slug($state))),
                Textarea::make('description')
                    ->label('Краткое описание')
                    ->rows(3)
                    ->columnSpanFull(),

                // === HTML КОНТЕНТ ===
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
                ColorPicker::make('color')
                    ->required()
                    ->default('#06b6d4')
                    ->hex()
                    ->formatStateUsing(fn ($state) => $state ?: '#06b6d4')
                    ->live()
                    ->helperText('Выберите цвет для услуги'),
                Placeholder::make('icon_preview')
                    ->label('Превью иконки')
                    ->content(function ($get) {
                        $icon = $get('icon') ?: 'business';
                        $color = $get('color') ?: '#06b6d4';

                        return new \Illuminate\Support\HtmlString(
                            '<div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg border border-gray-200">' .
                            '<div class="inline-flex items-center justify-center w-10 h-10 rounded-lg shadow-sm border" style="background-color: ' . $color . '20; border-color: ' . $color . '40;">' .
                            '<i class="material-icons text-xl" style="color: ' . $color . '">' . $icon . '</i>' .
                            '</div>' .
                            '<div class="flex flex-col">' .
                            '<span class="text-sm font-medium text-gray-900">' . $icon . '</span>' .
                            '<span class="text-xs text-gray-500">' . $color . '</span>' .
                            '</div>' .
                            '</div>'
                        );
                    })
                    ->hidden(fn ($get) => empty($get('icon'))),
                TextInput::make('icon')
                    ->live()
                    ->helperText('Введите название иконки Material Icons (например: business, trending_up, web)')
                    ->placeholder('business'),
                FileUpload::make('image')
                    ->image()
                    ->disk('public')
                    ->directory('images')
                    ->visibility('public'),
                TextInput::make('price_from')
                    ->numeric()
                    ->prefix('₽')
                    ->helperText('Укажите стартовую цену или оставьте пустым для "По договоренности"'),
                Select::make('price_type')
                    ->options([
                        'project' => 'За проект',
                        'hour' => 'За час',
                        'month' => 'За месяц',
                    ])
                    ->default('project')
                    ->required(),
                Repeater::make('features')
                    ->simple(
                TextInput::make('feature')
                            ->placeholder('Особенность услуги')
                    )
                    ->addActionLabel('Добавить особенность')
                    ->columnSpanFull()
                    ->grid(2),

                // === SEO НАСТРОЙКИ ===
                TextInput::make('meta_title')
                    ->label('Meta Title')
                    ->helperText('Заголовок для поисковых систем')
                    ->columnSpanFull(),
                Textarea::make('meta_description')
                    ->label('Meta Description')
                    ->rows(3)
                    ->helperText('Описание для поисковых систем')
                    ->columnSpanFull(),
                TextInput::make('meta_keywords')
                    ->label('Meta Keywords')
                    ->helperText('Ключевые слова через запятую')
                    ->columnSpanFull(),

                // === НАСТРОЙКИ ПУБЛИКАЦИИ ===
                Toggle::make('is_published')
                    ->label('Опубликовано')
                    ->default(true)
                    ->helperText('Услуга будет доступна на сайте'),
                Toggle::make('is_featured')
                    ->label('Рекомендуемая')
                    ->helperText('Услуга будет отмечена как рекомендуемая'),
                Toggle::make('show_on_homepage')
                    ->label('Показывать на главной')
                    ->helperText('Услуга будет отображаться в секции услуг на главной странице'),
                TextInput::make('sort_order')
                    ->label('Порядок сортировки')
                    ->numeric()
                    ->default(0)
                    ->helperText('Чем меньше число, тем выше в списке'),

                // === 🔗 СВЯЗИ ===

                // Заголовок для связанных услуг
                Placeholder::make('services_header')
                    ->content(new HtmlString('<div class="text-lg font-semibold text-gray-800 mb-4 mt-6 flex items-center"><span class="mr-2">🔗</span> Связанные услуги</div>'))
                    ->columnSpanFull(),

                // Связанные услуги
                TextInput::make('related_service_1_id')
                    ->label('ID связанной услуги #1')
                    ->numeric()
                    ->helperText('Введите ID услуги для отображения в блоке "Другие услуги"'),

                TextInput::make('related_service_2_id')
                    ->label('ID связанной услуги #2')
                    ->numeric()
                    ->helperText('Введите ID услуги для отображения в блоке "Другие услуги"'),

                TextInput::make('related_service_3_id')
                    ->label('ID связанной услуги #3')
                    ->numeric()
                    ->helperText('Введите ID услуги для отображения в блоке "Другие услуги"'),

                // Заголовок для полезных статей
                Placeholder::make('articles_header')
                    ->content(new HtmlString('<div class="text-lg font-semibold text-gray-800 mb-4 mt-6 flex items-center"><span class="mr-2">📰</span> Полезные статьи</div>'))
                    ->columnSpanFull(),

                // Полезные статьи
                TextInput::make('related_article_1_id')
                    ->label('ID полезной статьи #1')
                    ->numeric()
                    ->helperText('Введите ID статьи для отображения в блоке "Полезные статьи"'),

                TextInput::make('related_article_2_id')
                    ->label('ID полезной статьи #2')
                    ->numeric()
                    ->helperText('Введите ID статьи для отображения в блоке "Полезные статьи"'),

                TextInput::make('related_article_3_id')
                    ->label('ID полезной статьи #3')
                    ->numeric()
                    ->helperText('Введите ID статьи для отображения в блоке "Полезные статьи"'),

                // Заголовок для связанных кейсов
                Placeholder::make('cases_header')
                    ->content(new HtmlString('<div class="text-lg font-semibold text-gray-800 mb-4 mt-6 flex items-center"><span class="mr-2">💼</span> Связанные кейсы</div>'))
                    ->columnSpanFull(),

                // Связанные кейсы
                TextInput::make('related_case_1_id')
                    ->label('ID связанного кейса #1')
                    ->numeric()
                    ->helperText('Введите ID кейса для отображения в блоке "Наши кейсы"'),

                TextInput::make('related_case_2_id')
                    ->label('ID связанного кейса #2')
                    ->numeric()
                    ->helperText('Введите ID кейса для отображения в блоке "Наши кейсы"'),

                TextInput::make('related_case_3_id')
                    ->label('ID связанного кейса #3')
                    ->numeric()
                    ->helperText('Введите ID кейса для отображения в блоке "Наши кейсы"'),
            ]);
    }
}
