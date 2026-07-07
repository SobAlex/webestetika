<?php

namespace App\Filament\Resources\Blogs\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Placeholder;
use Filament\Forms;
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
                TextInput::make('title')
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
                Textarea::make('excerpt')
                    ->label('Краткое описание')
                    ->rows(3)
                    ->columnSpanFull(),
                // === HTML КОНТЕНТ ===
                Textarea::make('content')
                    ->label('Контент (HTML)')
                    ->rows(8)
                    ->live(onBlur: true)
                    ->helperText('Вводите HTML-код. Используйте теги: <p>, <h2>, <h3>, <ul>, <li>, <strong>, <em>, <a>')
                    ->columnSpanFull(),

                Placeholder::make('content_preview')
                    ->label('Предпросмотр контента')
                    ->content(function ($get) {
                        $content = $get('content');
                        if (!$content) {
                            return new HtmlString('<div class="text-gray-500 italic p-4 bg-gray-50 rounded-lg">Введите контент выше для предпросмотра</div>');
                        }

                        return new HtmlString(
                            '<div class="p-4 bg-white border rounded-lg shadow-sm" style="max-height: 300px; overflow-y: auto;">' .
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
                // Минимальный тестовый пример для отладки
                FileUpload::make('image')
                    ->image()
                    ->disk('public')
                    ->directory('images')
                    ->visibility('public'),
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
                TextInput::make('meta_title')
                    ->label('SEO заголовок')
                    ->helperText('Заголовок для поисковых систем (если не указан, будет использован обычный заголовок)'),
                Textarea::make('meta_description')
                    ->label('SEO описание')
                    ->helperText('Описание для поисковых систем (если не указано, будет использовано краткое описание)')
                    ->columnSpanFull(),
                Toggle::make('is_published')
                    ->required(),
                TextInput::make('sort_order')
                    ->required()
                    ->numeric()
                    ->default(0),
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                DateTimePicker::make('published_at'),
            ]);
    }
}
