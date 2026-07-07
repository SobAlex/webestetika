<?php

namespace App\Filament\Resources\Contacts\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ContactForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // Основная информация
                TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required()
                    ->maxLength(255)
                    ->columnSpan(1),

                TextInput::make('phone')
                    ->label('Телефон')
                    ->tel()
                    ->required()
                    ->maxLength(50)
                    ->columnSpan(1),

                Textarea::make('address')
                    ->label('Адрес')
                    ->rows(3)
                    ->maxLength(500)
                    ->columnSpanFull(),

                TextInput::make('working_hours')
                    ->label('Часы работы')
                    ->maxLength(100)
                    ->columnSpanFull(),

                // Социальные сети
                TextInput::make('social_telegram')
                    ->label('Telegram')
                    ->url()
                    ->maxLength(255)
                    ->columnSpan(1),

                TextInput::make('social_whatsapp')
                    ->label('WhatsApp')
                    ->url()
                    ->maxLength(255)
                    ->columnSpan(1),

                TextInput::make('social_vk')
                    ->label('ВКонтакте')
                    ->url()
                    ->maxLength(255)
                    ->columnSpan(1),

                TextInput::make('social_instagram')
                    ->label('Instagram')
                    ->url()
                    ->maxLength(255)
                    ->columnSpan(1),

                TextInput::make('social_youtube')
                    ->label('YouTube')
                    ->url()
                    ->maxLength(255)
                    ->columnSpanFull(),

                // Настройки
                Toggle::make('is_active')
                    ->label('Активен')
                    ->default(true)
                    ->helperText('Включить/выключить отображение контактов на сайте')
                    ->columnSpanFull(),
            ]);
    }
}
