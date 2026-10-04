<?php

namespace App\Http\Controllers;

use App\Services\BlogService;

class BlogController extends Controller
{
    public function __construct(
        private BlogService $blogService
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
     * Показать опубликованную статью.
     */
    public function show($category, $slug)
    {
        $article = $this->blogService->getPostByCategoryAndSlug($category, $slug);

        if (!$article) {
            abort(404);
        }

        $relatedArticles = $this->blogService->getRelatedPosts($article);

        return view('blog.article', compact('article', 'relatedArticles'));
    }

    /**
     * Показать опубликованную статью без категории.
     */
    public function showWithoutCategory($slug)
    {
        $article = $this->blogService->getPostBySlug($slug);

        if (!$article) {
            abort(404);
        }

        $relatedArticles = $this->blogService->getRelatedPosts($article);

        return view('blog.article', compact('article', 'relatedArticles'));
    }
}
