<?php

namespace App\Services;

use App\Jobs\SendContactEmail;
use App\Jobs\SendServiceOrderEmail;
use App\Models\Contact;
use Illuminate\Http\UploadedFile;

class ContactService
{

    /**
     * Get contact information formatted for display.
     */
    public function getContactInfo(): array
    {
        $contact = Contact::getContactData();

        return [
            'address' => $contact['address'] ?? 'Адрес не указан',
            'phone' => $contact['phone'] ?? 'Телефон не указан',
            'email' => $contact['email'] ?? 'Email не указан',
            'working_hours' => $contact['working_hours'] ?? 'Часы работы не указаны',
            'social' => $contact['social'] ?? []
        ];
    }

    // ниже не разобрано

    /**
     * Get all contact settings.
     */
    public function getContactSettings(): array
    {
        return Contact::getContactData();
    }

    /**
     * Get contact page data.
     */
    public function getContactPageData(): array
    {
        return [
            'title' => 'Контакты',
            'contactInfo' => $this->getContactInfo()
        ];
    }

    /**
     * Отправка заявки с hero-блока (имя + телефон).
     */
    public function sendHeroContact(array $data): void
    {
        SendContactEmail::dispatch([
            'subject' => 'Заявка (Hero) с сайта',
            'name' => $data['name'],
            'email' => null,
            'phone' => $data['phone'],
            'messageBody' => null,
        ]);
    }

    /**
     * Отправка сообщения с контактной формы.
     */
    public function sendContactForm(array $data): void
    {
        SendContactEmail::dispatch([
            'subject' => 'Сообщение с формы контактов',
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'messageBody' => $data['message'],
        ]);
    }

    /**
     * Отправка заказа услуги.
     */
    public function sendServiceOrder(array $data, ?UploadedFile $attachment = null): void
    {
        // Handle file upload if present
        $attachmentPath = null;
        $attachmentName = null;
        $attachmentMime = null;

        if ($attachment) {
            $attachmentPath = $attachment->store('service-orders', 'public');
            $attachmentName = $attachment->getClientOriginalName();
            $attachmentMime = $attachment->getMimeType();
        }

        // Отправляем письмо в очередь
        SendServiceOrderEmail::dispatch([
            'subject' => 'Заказ услуги: ' . $data['service_name'],
            'serviceName' => $data['service_name'],
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'messageBody' => $data['message'] ?? null,
            'attachmentPath' => $attachmentPath,
            'attachmentName' => $attachmentName,
            'attachmentMime' => $attachmentMime,
        ]);
    }
}
