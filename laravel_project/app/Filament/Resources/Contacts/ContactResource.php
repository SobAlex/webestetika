<?php

namespace App\Filament\Resources\Contacts;

use App\Filament\Resources\Contacts\Pages\EditContact;
use App\Filament\Resources\Contacts\Pages\ListContacts;
use App\Filament\Resources\Contacts\Schemas\ContactForm;
use App\Models\Contact;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class ContactResource extends Resource
{
    protected static ?string $model = Contact::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPhone;

    protected static ?string $navigationLabel = 'Контакты';

    protected static ?string $modelLabel = 'Контакт';

    protected static ?string $pluralModelLabel = 'Контакты';

    public static function form(Schema $schema): Schema
    {
        return ContactForm::configure($schema);
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
            'index' => ListContacts::route('/'),
            'edit' => EditContact::route('/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false; // Disable creation, only allow editing
    }
}
