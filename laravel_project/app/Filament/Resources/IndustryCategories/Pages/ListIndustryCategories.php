<?php

namespace App\Filament\Resources\IndustryCategories\Pages;

use App\Filament\Resources\IndustryCategories\IndustryCategoryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListIndustryCategories extends ListRecords
{
    protected static string $resource = IndustryCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
