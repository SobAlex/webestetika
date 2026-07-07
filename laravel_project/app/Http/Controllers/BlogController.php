<?php

namespace App\Http\Controllers;

use App\Services\BlogService;
use App\Http\Controllers\BlogCategoryController;

class BlogController extends Controller
{
    public function __construct(
        private BlogService $blogService,
        private BlogCategoryController $blogCategoryController
    ) {}

    // ============================================================================
    // PUBLIC ROUTES
    // ============================================================================

    /**
     * Главная страница блога
     */
    public function index()
    {
        $articles = $this->blogService->getPublishedPosts();
        $activeBlogCategories = $this->blogService->getActiveCategories();

        return view('blog.index', compact('articles', 'activeBlogCategories'));
    }

    /**
     * Универсальный метод для всех категорий блогов
     */
    public function category($categorySlug)
    {
        $data = $this->blogService->getPostsByCategory($categorySlug);

        if (!$data['blogCategory']) {
            abort(404);
        }

        return view('blog.category', $data);
    }

    /**
     * Показать конкретную статью (включая неопубликованные)
     */
    public function show($category, $slug)
    {
        $article = $this->blogService->getPostByCategoryAndSlugWithUnpublished($category, $slug);

        if (!$article) {
            abort(404);
        }

        $relatedArticles = $this->blogService->getRelatedPosts($article);

        return view('blog.article', compact('article', 'relatedArticles'));
    }

    /**
     * Показать статью без категории (включая неопубликованные)
     */
    public function showWithoutCategory($slug)
    {
        $article = $this->blogService->getPostBySlugWithUnpublished($slug);

        if (!$article) {
            abort(404);
        }

        $relatedArticles = $this->blogService->getRelatedPosts($article);

        return view('blog.article', compact('article', 'relatedArticles'));
    }

    // ============================================================================
    // LEGACY METHODS (для обратной совместимости)
    // ============================================================================

    /**
     * SEO новости (legacy)
     * @deprecated Используйте /blog/category/seo-news
     */
    public function seoNews()
    {
        return $this->category('seo-news');
    }

    /**
     * Аналитика (legacy)
     * @deprecated Используйте /blog/category/analytics
     */
    public function analytics()
    {
        return $this->category('analytics');
    }

    /**
     * Советы (legacy)
     * @deprecated Используйте /blog/category/tips
     */
    public function tips()
    {
        return $this->category('tips');
    }

    // ============================================================================
    // API METHODS
    // ============================================================================

    /**
     * Получить последние статьи для главной страницы
     */
    public function getLatestArticlesForHomepage($limit = 4)
    {
        return $this->blogService->getLatestPostsForHomepage($limit);
    }
}
