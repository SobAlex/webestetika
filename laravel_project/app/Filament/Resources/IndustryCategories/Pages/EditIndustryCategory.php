<?php

namespace App\Filament\Resources\IndustryCategories\Pages;

use App\Filament\Resources\IndustryCategories\IndustryCategoryResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditIndustryCategory extends EditRecord
{
    protected static string $resource = IndustryCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
