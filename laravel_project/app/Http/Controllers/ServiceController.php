<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\Service;

class ServiceController extends Controller
{
    /**
     * Display a listing of all services.
     */
    public function index()
    {
        $services = Service::published()->ordered()->get();

        return view('services.index', [
            'title' => 'Наши услуги',
            'services' => $services
        ]);
    }

    /**
     * Display the specified service.
     */
    public function show(Service $service)
    {
        if (!$service->isPublished()) {
            abort(404);
        }

        // Получаем связанные услуги через новую систему
        $relatedServices = $service->related_services;

        $servicesFaqs = Faq::visibleOnServices()->get();

        // Получаем связанные статьи
        $relatedArticles = $service->related_articles;

        // Получаем связанные кейсы
        $relatedCases = $service->related_cases;

        return view('services.show', [
            'title' => $service->meta_title ?: $service->title,
            'service' => $service,
            'relatedServices' => $relatedServices,
            'relatedArticles' => $relatedArticles,
            'relatedCases' => $relatedCases,
            'servicesFaqs' => $servicesFaqs
        ]);
    }
}
