<?php

namespace App\Filament\Resources\WhyUs\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;

class WhyUsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Заголовок')
                    ->searchable()
                    ->limit(30),
                TextColumn::make('icon')
                    ->label('Иконка')
                    ->formatStateUsing(function ($state, $record) {
                        if (! $state) {
                            return '-';
                        }

                        $color = $record->color ?: '#06b6d4';

                        return new HtmlString(
                            '<div class="flex items-center space-x-2">' .
                            '<div class="inline-flex items-center justify-center w-8 h-8 rounded" style="background-color: ' . e($color) . '20; border: 1px solid ' . e($color) . '40;">' .
                            '<i class="material-icons text-sm" style="color: ' . e($color) . '">' . e($state) . '</i>' .
                            '</div>' .
                            '<span class="text-xs text-gray-500">' . e($state) . '</span>' .
                            '</div>'
                        );
                    }),
                IconColumn::make('is_active')
                    ->label('Активен')
                    ->boolean(),
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
