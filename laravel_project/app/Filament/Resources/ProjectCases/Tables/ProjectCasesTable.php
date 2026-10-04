<?php

namespace App\Filament\Resources\ProjectCases\Tables;

use App\Filament\Resources\ProjectCases\ProjectCaseResource;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ProjectCasesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Заголовок')
                    ->searchable()
                    ->url(fn ($record) => ProjectCaseResource::getUrl('edit', ['record' => $record])),
                TextColumn::make('client')
                    ->label('Клиент')
                    ->searchable(),
                TextColumn::make('industryCategory.name')
                    ->label('Отрасль')
                    ->searchable(),
                IconColumn::make('is_published')
                    ->label('Опубликован')
                    ->boolean(),
            ])
            ->filters([
                TernaryFilter::make('is_published')
                    ->label('Статус публикации')
                    ->placeholder('Все кейсы')
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
