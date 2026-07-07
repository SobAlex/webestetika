<?php

namespace App\Filament\Resources\WhyUs\Pages;

use App\Filament\Resources\WhyUs\WhyUsResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditWhyUs extends EditRecord
{
    protected static string $resource = WhyUsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
