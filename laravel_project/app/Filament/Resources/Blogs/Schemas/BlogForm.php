<?php

namespace App\Filament\Resources\Blogs\Schemas;

use App\Models\Blog;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class BlogForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('SEO')
                    ->description('Заголовок и описание для поисковых систем. Если их не заполнить, на сайте используются обычный заголовок и краткое описание.')
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
                Section::make('Материал')
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        TextInput::make('title')
                            ->label('Заголовок')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (string $operation, $state, $set) {
                                if ($operation !== 'create') {
                                    return;
                                }
                                $set('slug', Str::slug($state));
                            }),
                        TextInput::make('slug')
                            ->label('Адрес')
                            ->required()
                            ->unique(Blog::class, 'slug', ignoreRecord: true)
                            ->helperText('Часть адреса страницы. При создании заполняется из заголовка.'),
                        Select::make('category_id')
                            ->label('Категория')
                            ->relationship('blogCategory', 'name', function ($query) {
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
                            ->visibility('public')
                            ->multiple(false)
                            ->maxFiles(1)
                            ->enableOpen()
                            ->preserveFilenames()
                            ->afterStateHydrated(function ($component, $state) {
                                if (is_array($state)) {
                                    $state = $state[array_key_first($state)] ?? null;
                                }
                                if (is_string($state) && filter_var($state, FILTER_VALIDATE_URL)) {
                                    $state = basename(parse_url($state, PHP_URL_PATH));
                                }
                                if (empty($state)) {
                                    $state = null;
                                }
                                $component->state($state);
                            })
                            ->dehydrateStateUsing(function ($state) {
                                if (is_array($state)) {
                                    return $state[array_key_first($state)] ?? null;
                                }

                                return $state ?: null;
                            }),
                        Textarea::make('excerpt')
                            ->label('Краткое описание')
                            ->rows(3)
                            ->columnSpanFull(),
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
                    ]),
                Section::make('Публикация')
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        Toggle::make('is_published')
                            ->label('Опубликована')
                            ->required(),
                        DateTimePicker::make('published_at')
                            ->label('Дата публикации'),
                    ]),
            ]);
    }
}
