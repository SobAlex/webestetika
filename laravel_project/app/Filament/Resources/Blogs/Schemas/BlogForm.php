<?php

namespace App\Filament\Resources\Blogs\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Placeholder;
use Filament\Schemas\Schema;
use App\Models\Blog;
use Illuminate\Support\Str;
use Illuminate\Support\HtmlString;

class BlogForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('meta_title')
                    ->label('SEO заголовок')
                    ->helperText('Заголовок для поисковых систем (если не указан, будет использован обычный заголовок)'),
                Textarea::make('meta_description')
                    ->label('SEO описание')
                    ->helperText('Описание для поисковых систем (если не указано, будет использовано краткое описание)')
                    ->columnSpanFull(),
                TextInput::make('title')
                    ->label('H1')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (string $operation, $state, $set) {
                        if ($operation !== 'create') {
                            return;
                        }
                        $set('slug', Str::slug($state));
                    }),
                TextInput::make('slug')
                    ->required()
                    ->unique(Blog::class, 'slug', ignoreRecord: true),
                // === ИЗОБРАЖЕНИЕ (финальная версия) ===
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
                Select::make('category_id')
                    ->relationship('blogCategory', 'name', function ($query) {
                        $query->where('is_active', true);
                    })
                    ->required()
                    ->searchable()
                    ->preload()
                    ->options(function () {
                        return \App\Models\BlogCategory::active()
                            ->get()
                            ->mapWithKeys(fn ($category) => [
                                $category->id => $category->name
                            ]);
                    }),
                TextInput::make('sort_order')
                    ->required()
                    ->numeric()
                    ->default(0),
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                DateTimePicker::make('published_at'),
                Toggle::make('is_published')
                    ->required(),
            ]);
    }
}
