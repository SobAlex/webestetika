<?php

namespace App\Filament\Resources\ProjectCases\Pages;

use App\Filament\Resources\ProjectCases\ProjectCaseResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListProjectCases extends ListRecords
{
    protected static string $resource = ProjectCaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
