<?php

namespace App\Filament\Resources\Services\Schemas;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;

class ServiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('SEO')
                    ->description('Заголовок и описание для поисковых систем.')
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('meta_title')
                            ->label('SEO заголовок')
                            ->columnSpanFull(),
                        Textarea::make('meta_description')
                            ->label('SEO описание')
                            ->rows(3)
                            ->columnSpanFull(),
                        TextInput::make('meta_keywords')
                            ->label('Ключевые слова')
                            ->helperText('Через запятую')
                            ->columnSpanFull(),
                    ]),
                Section::make('Карточка')
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        TextInput::make('title')
                            ->label('Заголовок')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (string $operation, $state, callable $set) {
                                if ($operation !== 'create') {
                                    return;
                                }
                                $set('slug', \Illuminate\Support\Str::slug($state));
                            }),
                        TextInput::make('slug')
                            ->label('Адрес')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (callable $set, $state) => $set('slug', \Illuminate\Support\Str::slug($state)))
                            ->helperText('Часть адреса страницы. При создании заполняется из заголовка.'),
                        Textarea::make('description')
                            ->label('Краткое описание')
                            ->rows(3)
                            ->columnSpanFull(),
                        FileUpload::make('image')
                            ->label('Изображение')
                            ->image()
                            ->disk('public')
                            ->directory('images')
                            ->visibility('public'),
                        TextInput::make('icon')
                            ->label('Иконка')
                            ->live()
                            ->helperText('Название иконки Material Icons, например business')
                            ->placeholder('business'),
                        ColorPicker::make('color')
                            ->label('Цвет')
                            ->required()
                            ->default('#06b6d4')
                            ->hex()
                            ->formatStateUsing(fn ($state) => $state ?: '#06b6d4')
                            ->live()
                            ->helperText('Цвет иконки услуги'),
                        Placeholder::make('icon_preview')
                            ->label('Превью иконки')
                            ->content(function ($get) {
                                $icon = e($get('icon') ?: 'business');
                                $color = e($get('color') ?: '#06b6d4');

                                return new HtmlString(
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
                            ->hidden(fn ($get) => empty($get('icon')))
                            ->columnSpanFull(),
                    ]),
                Section::make('Текст и цена')
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        RichEditor::make('content')
                            ->label('Текст')
                            ->toolbarButtons([
                                ['bold', 'italic', 'underline', 'strike', 'subscript', 'superscript', 'link'],
                                ['h1', 'h2', 'h3', 'h4', 'alignStart', 'alignCenter', 'alignEnd', 'alignJustify'],
                                ['bulletList', 'orderedList', 'blockquote', 'codeBlock', 'code'],
                                ['attachFiles', 'table', 'horizontalRule', 'details'],
                                ['textColor', 'highlight', 'clearFormatting'],
                                ['undo', 'redo'],
                            ])
                            ->fileAttachmentsDisk('public')
                            ->fileAttachmentsDirectory('attachments')
                            ->fileAttachmentsVisibility('public')
                            ->resizableImages()
                            ->columnSpanFull(),
                        TextInput::make('price_from')
                            ->label('Цена от')
                            ->numeric()
                            ->prefix('₽')
                            ->helperText('Пустое поле на сайте показывается как «По договоренности»'),
                        Select::make('price_type')
                            ->label('Тип цены')
                            ->options([
                                'project' => 'За проект',
                                'hour' => 'За час',
                                'month' => 'За месяц',
                            ])
                            ->default('project'),
                        Repeater::make('features')
                            ->label('Особенности')
                            ->simple(
                                TextInput::make('feature')
                                    ->label('Особенность')
                                    ->hiddenLabel()
                                    ->placeholder('Особенность услуги')
                            )
                            ->addActionLabel('Добавить особенность')
                            ->columnSpanFull()
                            ->grid(2),
                    ]),
                Section::make('Связанные материалы')
                    ->columnSpanFull()
                    ->schema([
                        Fieldset::make('Услуги')
                            ->columnSpanFull()
                            ->columns(3)
                            ->schema([
                                Select::make('related_service_1_id')
                                    ->label('Услуга 1')
                                    ->relationship('relatedService1', 'title')
                                    ->searchable()
                                    ->preload(),
                                Select::make('related_service_2_id')
                                    ->label('Услуга 2')
                                    ->relationship('relatedService2', 'title')
                                    ->searchable()
                                    ->preload(),
                                Select::make('related_service_3_id')
                                    ->label('Услуга 3')
                                    ->relationship('relatedService3', 'title')
                                    ->searchable()
                                    ->preload(),
                            ]),
                        Fieldset::make('Статьи')
                            ->columnSpanFull()
                            ->columns(3)
                            ->schema([
                                Select::make('related_article_1_id')
                                    ->label('Статья 1')
                                    ->relationship('relatedArticle1', 'title')
                                    ->searchable()
                                    ->preload(),
                                Select::make('related_article_2_id')
                                    ->label('Статья 2')
                                    ->relationship('relatedArticle2', 'title')
                                    ->searchable()
                                    ->preload(),
                                Select::make('related_article_3_id')
                                    ->label('Статья 3')
                                    ->relationship('relatedArticle3', 'title')
                                    ->searchable()
                                    ->preload(),
                            ]),
                        Fieldset::make('Кейсы')
                            ->columnSpanFull()
                            ->columns(3)
                            ->schema([
                                Select::make('related_case_1_id')
                                    ->label('Кейс 1')
                                    ->relationship('relatedCase1', 'title')
                                    ->searchable()
                                    ->preload(),
                                Select::make('related_case_2_id')
                                    ->label('Кейс 2')
                                    ->relationship('relatedCase2', 'title')
                                    ->searchable()
                                    ->preload(),
                                Select::make('related_case_3_id')
                                    ->label('Кейс 3')
                                    ->relationship('relatedCase3', 'title')
                                    ->searchable()
                                    ->preload(),
                            ]),
                    ]),
                Section::make('Публикация')
                    ->columnSpanFull()
                    ->columns(3)
                    ->schema([
                        Toggle::make('is_published')
                            ->label('Опубликована')
                            ->default(true),
                        Toggle::make('is_featured')
                            ->label('Рекомендуемая'),
                        Toggle::make('show_on_homepage')
                            ->label('На главной'),
                    ]),
            ]);
    }
}
