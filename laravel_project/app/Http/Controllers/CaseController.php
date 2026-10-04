<?php

namespace App\Http\Controllers;

use App\Services\CaseService;

class CaseController extends Controller
{
    public function __construct(
        private CaseService $caseService
    ) {}

    /**
     * Главная страница кейсов
     */
    public function index()
    {
        $cases = $this->caseService->getAllCases();
        $casesData = [
            'seo-promotion' => [
                'service_name' => 'SEO продвижение',
                'service_icon' => 'trending_up',
                'cases' => $this->caseService->transformCasesForTemplate($cases)
            ]
        ];

        $title = 'Кейсы SEO продвижения';
        $selectedTag = null;
        $activeCategories = $this->caseService->getActiveCategories();

        return view('cases.index', compact('casesData', 'title', 'selectedTag', 'activeCategories'));
    }

    /**
     * Универсальный метод для всех категорий отраслей
     */
    public function category($industry)
    {
        $categoryInfo = $this->caseService->getCategoryInfo($industry);

        if (!$categoryInfo) {
            abort(404, 'Категория не найдена');
        }

        $title = 'Кейсы SEO продвижения: ' . $categoryInfo['name'];
        return $this->filterByTag($industry, $title);
    }

    /**
     * Фильтрация кейсов по тегу
     */
    private function filterByTag($tag, $title)
    {
        $cases = $this->caseService->getCasesByIndustry($tag);
        $casesData = [
            'seo-promotion' => [
                'service_name' => 'SEO продвижение',
                'service_icon' => 'trending_up',
                'cases' => $this->caseService->transformCasesForTemplate($cases)
            ]
        ];

        $selectedTag = $tag;
        $categoryInfo = $this->caseService->getCategoryInfo($tag);
        $activeCategories = $this->caseService->getActiveCategories();

        return view('cases.index', compact('casesData', 'title', 'selectedTag', 'categoryInfo', 'activeCategories'));
    }

    /**
     * Страница опубликованного кейса.
     */
    public function show($id)
    {
        $case = $this->caseService->getCaseById($id);

        if (!$case) {
            abort(404);
        }

        $caseData = $this->caseService->transformCaseForTemplate($case);
        $categoryInfo = $this->caseService->getCategoryInfo($caseData['industry']);

        // Получаем данные о сервисе из настроек кейса или используем значения по умолчанию
        $serviceData = [
            'service_name' => $case->service_link_text ?: 'SEO продвижение',
            'service_icon' => 'trending_up',
            'service_slug' => $case->service_link_url ?: '/services/seo-prodvizhenie'
        ];

        return view('cases.show', compact('caseData', 'serviceData', 'categoryInfo'));
    }
}
