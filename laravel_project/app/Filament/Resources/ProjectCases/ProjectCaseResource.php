<?php

namespace App\Filament\Resources\ProjectCases;

use App\Filament\Resources\ProjectCases\Pages\CreateProjectCase;
use App\Filament\Resources\ProjectCases\Pages\EditProjectCase;
use App\Filament\Resources\ProjectCases\Pages\ListProjectCases;
use App\Filament\Resources\ProjectCases\Schemas\ProjectCaseForm;
use App\Filament\Resources\ProjectCases\Tables\ProjectCasesTable;
use App\Models\ProjectCase;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ProjectCaseResource extends Resource
{
    protected static ?string $model = ProjectCase::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Кейс';

    public static function form(Schema $schema): Schema
    {
        return ProjectCaseForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProjectCasesTable::configure($table);
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
            'index' => ListProjectCases::route('/'),
            'create' => CreateProjectCase::route('/create'),
            'edit' => EditProjectCase::route('/{record}/edit'),
        ];
    }
}
