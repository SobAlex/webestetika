<?php

namespace App\Filament\Resources\Contacts\Pages;

use App\Filament\Resources\Contacts\ContactResource;
use App\Models\Contact;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;

class ListContacts extends ListRecords
{
    protected static string $resource = ContactResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('edit')
                ->label('Редактировать контакты')
                ->url(fn () => ContactResource::getUrl('edit', ['record' => Contact::getInstance()]))
                ->icon('heroicon-o-pencil')
                ->color('primary'),
        ];
    }

    public function mount(): void
    {
        parent::mount();

        // Ensure contact record exists
        Contact::getInstance();
    }
}
