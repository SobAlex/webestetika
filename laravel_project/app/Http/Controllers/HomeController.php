<?php

namespace App\Http\Controllers;

use App\Services\HeroService;
use App\Services\ServiceService;
use App\Services\WhyUsService;
use App\Services\CaseService;
use App\Services\BlogService;
use App\Services\FaqService;
use App\Services\ReviewService;

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

        return view('welcome', compact(
            'activeHeroes',
            'featuredServices',
            'whyUsBlocks',
            'transformlatestCases',
            'latestArticles',
            'homepageFaqs',
            'randomReviews',
        ));
    }
}
