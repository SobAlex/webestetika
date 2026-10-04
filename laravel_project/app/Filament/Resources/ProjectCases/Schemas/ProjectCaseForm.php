<?php

namespace App\Filament\Resources\ProjectCases\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProjectCaseForm
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
                    ]),
                Section::make('Карточка')
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        TextInput::make('title')
                            ->label('Заголовок')
                            ->required(),
                        TextInput::make('client')
                            ->label('Клиент')
                            ->required(),
                        TextInput::make('period')
                            ->label('Период')
                            ->required(),
                        Select::make('industry_category_id')
                            ->label('Отрасль')
                            ->relationship('industryCategory', 'name', function ($query) {
                                $query->where('is_active', true);
                            })
                            ->required()
                            ->searchable()
                            ->preload(),
                        FileUpload::make('image')
                            ->label('Изображение')
                            ->image()
                            ->disk('public')
                            ->directory('images')
                            ->visibility('public'),
                        Textarea::make('description')
                            ->label('Описание проекта')
                            ->required()
                            ->rows(4)
                            ->columnSpanFull(),
                    ]),
                Section::make('Результаты')
                    ->columnSpanFull()
                    ->schema([
                        Repeater::make('results')
                            ->label('Результаты')
                            ->hiddenLabel()
                            ->simple(
                                TextInput::make('result')
                                    ->label('Результат')
                                    ->hiddenLabel()
                                    ->placeholder('Результат проекта')
                                    ->required()
                            )
                            ->addActionLabel('Добавить результат')
                            ->reorderableWithButtons()
                            ->collapsible()
                            ->grid(2),
                    ]),
                Section::make('Метрики')
                    ->description('Показатели до и после.')
                    ->columnSpanFull()
                    ->schema([
                        Repeater::make('metrics')
                            ->label('Метрики')
                            ->hiddenLabel()
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
                            ->grid(3),
                    ]),
                Section::make('Ссылка на услугу')
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        TextInput::make('service_link_text')
                            ->label('Текст ссылки')
                            ->placeholder('Например: SEO продвижение')
                            ->helperText('Текст ссылки под заголовком кейса'),
                        TextInput::make('service_link_url')
                            ->label('Адрес ссылки')
                            ->placeholder('Например: /services/seo-prodvizhenie')
                            ->helperText('Можно указать относительный адрес'),
                    ]),
                Section::make('Текст')
                    ->columnSpanFull()
                    ->schema([
                        RichEditor::make('content')
                            ->label('Текст')
                            ->hiddenLabel()
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
                    ]),
                Section::make('Публикация')
                    ->columnSpanFull()
                    ->schema([
                        Toggle::make('is_published')
                            ->label('Опубликован')
                            ->required(),
                    ]),
            ]);
    }
}
