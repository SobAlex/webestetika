<?php

namespace App\Filament\Resources\Services\Tables;

use App\Filament\Resources\Services\ServiceResource;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ServicesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('title')
                    ->searchable()
                    ->url(fn ($record) => ServiceResource::getUrl('edit', ['record' => $record]))
                    ->openUrlInNewTab(false),
                TextColumn::make('slug')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('icon')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('color')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                ImageColumn::make('image')
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('price_from')
                    ->money('RUB')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('price_type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'project' => 'success',
                        'hour' => 'warning',
                        'month' => 'info',
                        default => 'gray',
                    })
                    ->toggleable(isToggledHiddenByDefault: false),
                IconColumn::make('is_published')
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: false),
                IconColumn::make('is_featured')
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: false),
                IconColumn::make('show_on_homepage')
                    ->label('На главной')
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: false),
                TextInputColumn::make('sort_order')
                    ->rules(['integer', 'min:0'])
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('is_published')
                    ->label('Статус публикации')
                    ->placeholder('Все услуги')
                    ->trueLabel('Только опубликованные')
                    ->falseLabel('Только неопубликованные')
                    ->native(false),
                TernaryFilter::make('is_featured')
                    ->label('Рекомендуемые')
                    ->placeholder('Все услуги')
                    ->trueLabel('Только рекомендуемые')
                    ->falseLabel('Только обычные')
                    ->native(false),
                TernaryFilter::make('show_on_homepage')
                    ->label('На главной странице')
                    ->placeholder('Все услуги')
                    ->trueLabel('Только на главной')
                    ->falseLabel('Не на главной')
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
