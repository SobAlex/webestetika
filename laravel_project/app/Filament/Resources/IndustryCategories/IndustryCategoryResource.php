<?php

namespace App\Filament\Resources\IndustryCategories;

use App\Filament\Resources\IndustryCategories\Pages\CreateIndustryCategory;
use App\Filament\Resources\IndustryCategories\Pages\EditIndustryCategory;
use App\Filament\Resources\IndustryCategories\Pages\ListIndustryCategories;
use App\Filament\Resources\IndustryCategories\Schemas\IndustryCategoryForm;
use App\Filament\Resources\IndustryCategories\Tables\IndustryCategoriesTable;
use App\Models\IndustryCategory;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class IndustryCategoryResource extends Resource
{
    protected static ?string $model = IndustryCategory::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Категория кейса';

    public static function form(Schema $schema): Schema
    {
        return IndustryCategoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return IndustryCategoriesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListIndustryCategories::route('/'),
            'create' => CreateIndustryCategory::route('/create'),
            'edit' => EditIndustryCategory::route('/{record}/edit'),
        ];
    }
}
