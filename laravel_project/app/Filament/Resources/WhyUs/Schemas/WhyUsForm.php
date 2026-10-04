<?php

namespace App\Filament\Resources\WhyUs\Schemas;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;

class WhyUsForm
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
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
                Section::make('Оформление')
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        TextInput::make('icon')
                            ->label('Иконка')
                            ->live()
                            ->helperText('Название иконки Material Icons, например verified')
                            ->placeholder('verified'),
                        ColorPicker::make('color')
                            ->label('Цвет иконки')
                            ->required()
                            ->default('#06b6d4')
                            ->hex()
                            ->live(),
                        Placeholder::make('icon_preview')
                            ->label('Превью')
                            ->content(function ($get) {
                                $icon = e($get('icon') ?: 'verified');
                                $color = e($get('color') ?: '#06b6d4');

                                return new HtmlString(
                                    '<div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg border border-gray-200">' .
                                    '<div class="inline-flex items-center justify-center w-12 h-12 rounded-lg shadow-sm border" style="background-color: ' . $color . '20; border-color: ' . $color . '40;">' .
                                    '<i class="material-icons text-2xl" style="color: ' . $color . '">' . $icon . '</i>' .
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
                Section::make('Показ')
                    ->columnSpanFull()
                    ->schema([
                        Toggle::make('is_active')
                            ->label('Активен')
                            ->default(true)
                            ->helperText('Блок будет отображаться на сайте'),
                    ]),
            ]);
    }
}
