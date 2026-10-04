<?php

namespace App\Filament\Resources\Blogs\Tables;

use App\Filament\Resources\Blogs\BlogResource;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class BlogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Заголовок')
                    ->searchable()
                    ->url(fn ($record) => BlogResource::getUrl('edit', ['record' => $record])),
                TextColumn::make('blogCategory.name')
                    ->label('Категория')
                    ->searchable(),
                IconColumn::make('is_published')
                    ->label('Опубликована')
                    ->boolean(),
            ])
            ->filters([
                TernaryFilter::make('is_published')
                    ->label('Статус публикации')
                    ->placeholder('Все статьи')
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
