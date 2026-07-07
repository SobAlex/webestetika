<?php

namespace App\Filament\Resources\ProjectCases\Pages;

use App\Filament\Resources\ProjectCases\ProjectCaseResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditProjectCase extends EditRecord
{
    protected static string $resource = ProjectCaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
