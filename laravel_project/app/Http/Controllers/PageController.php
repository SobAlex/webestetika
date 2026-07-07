<?php

namespace App\Http\Controllers;

use App\Services\ContactService;

class PageController extends Controller
{
    public function __construct(
        private ContactService $contactService
    ) {}

    /**
     * Страница контактов
     */
    public function contacts()
    {
        $data = $this->contactService->getContactPageData();
        return view('pages.contacts', $data);
    }
}
