<?php

namespace App\Http\Controllers;

use App\Http\Requests\HeroRequest;
use App\Http\Requests\ContactRequest;
use App\Http\Requests\ServiceOrderRequest;
use App\Services\ContactService;

class ContactController extends Controller
{
    public function __construct(
        private readonly ContactService $contactService,
    ) {
    }

    /**
     * Handle hero form submission (name, phone).
     */
    public function submitHero(HeroRequest $request)
    {
        $validated = $request->validated();

        $this->contactService->sendHeroContact($validated);

        return back()->with('status', 'Спасибо! Мы свяжемся с вами.');
    }

    /**
     * Handle contact form submission (name, email, phone, message).
     */
    public function submitContact(ContactRequest $request)
    {
        $validated = $request->validated();

        $this->contactService->sendContactForm($validated);

        return back()->with('status', 'Сообщение отправлено!');
    }

    /**
     * Handle service order form submission.
     */
    public function submitServiceOrder(ServiceOrderRequest $request)
    {
        $validated = $request->validated();
        $attachment = $request->hasFile('attachment') ? $request->file('attachment') : null;

        $this->contactService->sendServiceOrder($validated, $attachment);

        return back()->with('status', 'Заявка на услугу отправлена! Мы свяжемся с вами в ближайшее время.');
    }
}
