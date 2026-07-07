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

        // dd($casesData);

        return view('cases.index', compact('casesData', 'title', 'selectedTag', 'activeCategories'));
    }

    /**
     * Кейсы по одежде
     */
    public function clothing()
    {
        return $this->filterByTag('clothing', 'Кейсы SEO продвижения: Одежда');
    }

    /**
     * Кейсы по производству
     */
    public function production()
    {
        return $this->filterByTag('production', 'Кейсы SEO продвижения: Производство');
    }

    /**
     * Кейсы по электронике
     */
    public function electronics()
    {
        return $this->filterByTag('electronics', 'Кейсы SEO продвижения: Электроника');
    }

    /**
     * Кейсы по мебели
     */
    public function furniture()
    {
        return $this->filterByTag('furniture', 'Кейсы SEO продвижения: Мебель');
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
     * Страница отдельного кейса (включая неопубликованные)
     */
    public function show($id)
    {
        $case = $this->caseService->getCaseByIdWithUnpublished($id);

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

    /**
     * Получить последние кейсы для главной страницы
     */
    public function getLatestCasesForHomepage()
    {
        $cases = $this->caseService->getLatestCasesForHomepage();
        return $this->caseService->transformCasesForTemplate($cases);
    }

    /**
     * Получить активные категории
     */
    public function getActiveCategories()
    {
        return $this->caseService->getActiveCategories();
    }
}
