<?php

namespace App\Filament\Resources\Contacts\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ContactsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('phone')
                    ->label('Телефон')
                    ->copyable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->copyable(),
                TextColumn::make('address')
                    ->label('Адрес')
                    ->limit(50),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->paginated(false);
    }
}
