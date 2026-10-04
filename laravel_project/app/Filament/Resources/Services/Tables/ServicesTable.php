<?php

namespace App\Filament\Resources\Services\Tables;

use App\Filament\Resources\Services\ServiceResource;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ServicesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Заголовок')
                    ->searchable()
                    ->url(fn ($record) => ServiceResource::getUrl('edit', ['record' => $record])),
                TextColumn::make('price_from')
                    ->label('Цена')
                    ->money('RUB')
                    ->sortable(),
                IconColumn::make('is_published')
                    ->label('Опубликована')
                    ->boolean(),
                IconColumn::make('show_on_homepage')
                    ->label('На главной')
                    ->boolean(),
            ])
            ->filters([
                TernaryFilter::make('is_published')
                    ->label('Статус публикации')
                    ->placeholder('Все услуги')
                    ->trueLabel('Только опубликованные')
                    ->falseLabel('Только неопубликованные')
                    ->native(false),
            ])
            ->reorderable('sort_order')
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
