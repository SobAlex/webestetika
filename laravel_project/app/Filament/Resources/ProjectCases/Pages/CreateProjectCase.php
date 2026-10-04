<?php

namespace App\Filament\Resources\ProjectCases\Pages;

use App\Filament\Resources\ProjectCases\ProjectCaseResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProjectCase extends CreateRecord
{
    protected static string $resource = ProjectCaseResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();

        return $data;
    }
}
