<?php

namespace App\Filament\Resources\Faqs\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class FaqForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Вопрос и ответ')
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('question')
                            ->label('Вопрос')
                            ->required()
                            ->columnSpanFull(),
                        Textarea::make('answer')
                            ->label('Ответ')
                            ->required()
                            ->rows(4)
                            ->columnSpanFull(),
                    ]),
                Section::make('Где показывать')
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        Toggle::make('show_on_homepage')
                            ->label('На главной')
                            ->default(true),
                        Toggle::make('show_on_services')
                            ->label('На страницах услуг')
                            ->default(true),
                        Toggle::make('is_active')
                            ->label('Активен')
                            ->required(),
                    ]),
            ]);
    }
}
