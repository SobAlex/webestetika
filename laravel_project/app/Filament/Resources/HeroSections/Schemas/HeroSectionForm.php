<?php

namespace App\Filament\Resources\HeroSections\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class HeroSectionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('Заголовок')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),

                Textarea::make('description')
                    ->label('Описание')
                    ->required()
                    ->rows(4)
                    ->columnSpanFull(),

                FileUpload::make('image')
                    ->label('Изображение')
                    ->image()
                    ->disk('public')
                    ->directory('images/hero')
                    ->visibility('public')
                    ->imageEditor()
                    ->columnSpanFull(),

                TextInput::make('button_text')
                    ->label('Текст кнопки')
                    ->required()
                    ->default('Получить консультацию')
                    ->maxLength(255)
                    ->helperText('Кнопка отправляет форму обратной связи'),

                // === НАСТРОЙКИ ОТОБРАЖЕНИЯ ===
                Toggle::make('is_active')
                    ->label('Активен')
                    ->default(true)
                    ->helperText('Только активные блоки отображаются на сайте'),

                TextInput::make('sort_order')
                    ->label('Порядок сортировки')
                    ->numeric()
                    ->default(0)
                    ->helperText('Чем меньше число, тем выше в списке'),
            ]);
    }
}
