<?php

namespace App\Filament\Resources\HeroSections\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class HeroSectionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Содержание')
                    ->columnSpanFull()
                    ->schema([
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
                        TextInput::make('button_text')
                            ->label('Текст кнопки')
                            ->required()
                            ->default('Получить консультацию')
                            ->maxLength(255)
                            ->helperText('Кнопка открывает форму обратной связи')
                            ->columnSpanFull(),
                    ]),
                Section::make('Оформление')
                    ->columnSpanFull()
                    ->schema([
                        FileUpload::make('image')
                            ->label('Изображение')
                            ->image()
                            ->disk('public')
                            ->directory('images/hero')
                            ->visibility('public')
                            ->imageEditor(),
                    ]),
                Section::make('Показ')
                    ->columnSpanFull()
                    ->schema([
                        Toggle::make('is_active')
                            ->label('Активен')
                            ->default(true)
                            ->helperText('На сайте показывается один активный слайд с наименьшим порядком в списке'),
                    ]),
            ]);
    }
}
