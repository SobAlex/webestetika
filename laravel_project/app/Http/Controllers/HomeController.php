<?php

namespace App\Http\Controllers;

use App\Services\HeroService;
use App\Services\ServiceService;
use App\Services\WhyUsService;
use App\Services\CaseService;
use App\Services\BlogService;
use App\Services\FaqService;
use App\Services\ReviewService;
use App\Services\ContactService;

class HomeController extends Controller
{
    public function __construct(

        private HeroService $heroService,
        private ServiceService $serviceService,
        private WhyUsService $whyUsService,
        private CaseService $caseService,
        private BlogService $blogService,
        private FaqService $faqService,
        private ReviewService $reviewService,
        private ContactService $contactService

    ) {}

    /**
     * Главная страница сайта
     */
    public function index()
    {
        // Собираем все необходимые данные
        $activeHeroes = $this->heroService->getActiveHeroSections();
        $featuredServices = $this->serviceService->getFeaturedServices();
        $whyUsBlocks = $this->whyUsService->getActiveWhyUsBlocks();
        $transformlatestCases = $this->caseService->getLatestCasesTransformedForHomepage();
        $latestArticles = $this->blogService->getLatestPostsForHomepage();
        $homepageFaqs = $this->faqService->getHomepageFaqs();
        $randomReviews = $this->reviewService->getRandomReviewsForHomepage();
        $contactInfo = $this->contactService->getContactInfo();

        // Получаем категории для навигации
        $activeCaseCategories = $this->caseService->getActiveCategories();
        $activeBlogCategories = $this->blogService->getActiveCategories();

        return view('welcome', compact(
            'activeHeroes',
            'featuredServices',
            'whyUsBlocks',
            'transformlatestCases',
            'latestArticles',
            'homepageFaqs',
            'randomReviews',
            'contactInfo',
            'activeCaseCategories',
            'activeBlogCategories'
        ));
    }
}
