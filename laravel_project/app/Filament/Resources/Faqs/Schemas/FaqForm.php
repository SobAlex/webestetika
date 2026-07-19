<?php

namespace App\Filament\Resources\Faqs\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class FaqForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('question')
                    ->required(),
                Textarea::make('answer')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('sort_order')
                    ->required()
                    ->numeric()
                    ->default(0),
                Toggle::make('show_on_homepage')
                    ->columnSpan(2)
                    ->label('Показывать на главной странице')
                    ->default(true),
                Toggle::make('show_on_services')
                    ->columnSpan(2)
                    ->label('Показывать на страницах услуг')
                    ->default(true),
                Toggle::make('is_active')
                    ->label('Активно')
                    ->required(),
            ]);
    }
}
