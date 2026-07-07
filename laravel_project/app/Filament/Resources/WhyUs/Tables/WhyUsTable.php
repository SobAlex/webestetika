<?php

namespace App\Filament\Resources\WhyUs\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;

class WhyUsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('sort_order')
                    ->label('Порядок')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('title')
                    ->label('Заголовок')
                    ->searchable()
                    ->sortable()
                    ->limit(30)
                    ->toggleable(),

                TextColumn::make('description')
                    ->label('Описание')
                    ->searchable()
                    ->limit(50)
                    ->toggleable(),

                TextColumn::make('icon')
                    ->label('Иконка')
                    ->formatStateUsing(function ($state, $record) {
                        if (!$state) return '-';

                        $color = $record->color ?: '#06b6d4';
                        return new HtmlString(
                            '<div class="flex items-center space-x-2">' .
                            '<div class="inline-flex items-center justify-center w-8 h-8 rounded" style="background-color: ' . $color . '20; border: 1px solid ' . $color . '40;">' .
                            '<i class="material-icons text-sm" style="color: ' . $color . '">' . $state . '</i>' .
                            '</div>' .
                            '<span class="text-xs text-gray-500">' . $state . '</span>' .
                            '</div>'
                        );
                    })
                    ->toggleable(),

                TextColumn::make('color')
                    ->label('Цвет')
                    ->formatStateUsing(function ($state) {
                        if (!$state) return '-';
                        return new HtmlString(
                            '<div class="flex items-center space-x-2">' .
                            '<div class="w-4 h-4 rounded border border-gray-300" style="background-color: ' . $state . '"></div>' .
                            '<span class="text-xs text-gray-500">' . $state . '</span>' .
                            '</div>'
                        );
                    })
                    ->toggleable(),


                IconColumn::make('is_active')
                    ->label('Активен')
                    ->boolean()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label('Создан')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Обновлен')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('is_active')
                    ->label('Статус')
                    ->boolean()
                    ->trueLabel('Только активные')
                    ->falseLabel('Только неактивные')
                    ->native(false),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->reorderable('sort_order')
            ->defaultSort('sort_order');
    }
}
