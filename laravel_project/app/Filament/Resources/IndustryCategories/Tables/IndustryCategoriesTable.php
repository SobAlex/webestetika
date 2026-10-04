<?php

namespace App\Filament\Resources\IndustryCategories\Tables;

use App\Filament\Resources\IndustryCategories\IndustryCategoryResource;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class IndustryCategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Название')
                    ->searchable()
                    ->url(fn ($record) => IndustryCategoryResource::getUrl('edit', ['record' => $record])),
                IconColumn::make('is_active')
                    ->label('Активна')
                    ->boolean(),
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
