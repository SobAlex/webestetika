<?php

namespace App\Filament\Resources\Contacts\Pages;

use App\Filament\Resources\Contacts\ContactResource;
use App\Models\Contact;
use Filament\Resources\Pages\EditRecord;

class EditContact extends EditRecord
{
    protected static string $resource = ContactResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Remove delete action since we don't want to delete the only contact record
        ];
    }

    public function mount(int|string $record = null): void
    {
        // Always use the singleton contact record
        $contact = Contact::getInstance();
        parent::mount($contact->id);
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Контакты успешно обновлены';
    }
}
