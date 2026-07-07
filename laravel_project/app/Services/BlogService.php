<?php

namespace App\Services;

use App\Models\Blog;
use App\Models\BlogCategory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection as SupportCollection;

class BlogService
{

    /**
     * Get latest posts for homepage.
     */
    public function getLatestPostsForHomepage(int $limit = 4): Collection
    {
        return Blog::published()
            ->with('blogCategory')
            ->whereHas('blogCategory', function($query) {
                $query->where('is_active', true);
            })
            ->ordered()
            ->limit($limit)
            ->get();
    }

    /**
     * Get all published blog posts with pagination.
     */
    public function getPublishedPosts(int $perPage = 12): LengthAwarePaginator
    {
        return Blog::published()
            ->with('blogCategory')
            ->ordered()
            ->paginate($perPage);
    }

    /**
     * Get active blog categories.
     */
    public function getActiveCategories(): SupportCollection
    {
        return BlogCategory::active()
            ->ordered()
            ->get()
            ->map(function ($category) {
                return [
                    'slug' => $category->slug,
                    'name' => $category->name,
                    'icon' => $category->icon ?: 'article',
                    'color' => $category->color,
                    'description' => $category->description
                ];
            });
    }

    // ниже пока не разобранные методы

    /**
     * Get posts by category.
     */
    public function getPostsByCategory(string $categorySlug, int $perPage = 12): array
    {
        $blogCategory = BlogCategory::where('slug', $categorySlug)
            ->where('is_active', true)
            ->first();

        if (!$blogCategory) {
            return [
                'articles' => collect(),
                'category' => null,
                'categorySlug' => $categorySlug,
                'blogCategory' => null,
                'activeBlogCategories' => $this->getActiveCategories()
            ];
        }

        $articles = Blog::published()
            ->with('blogCategory')
            ->whereHas('blogCategory', function($query) use ($categorySlug) {
                $query->where('slug', $categorySlug)->where('is_active', true);
            })
            ->ordered()
            ->paginate($perPage);

        return [
            'articles' => $articles,
            'category' => $blogCategory->name,
            'categorySlug' => $categorySlug,
            'blogCategory' => $blogCategory,
            'activeBlogCategories' => $this->getActiveCategories()
        ];
    }

    /**
     * Get blog post by category and slug (published only).
     */
    public function getPostByCategoryAndSlug(string $categorySlug, string $slug): ?Blog
    {
        return Blog::published()
            ->with('blogCategory')
            ->whereHas('blogCategory', function($query) use ($categorySlug) {
                $query->where('slug', $categorySlug)->where('is_active', true);
            })
            ->where('slug', $slug)
            ->first();
    }

    /**
     * Get blog post by category and slug (including unpublished).
     */
    public function getPostByCategoryAndSlugWithUnpublished(string $categorySlug, string $slug): ?Blog
    {
        return Blog::with('blogCategory')
            ->whereHas('blogCategory', function($query) use ($categorySlug) {
                $query->where('slug', $categorySlug);
            })
            ->where('slug', $slug)
            ->first();
    }

    /**
     * Get blog post by slug only (published only).
     */
    public function getPostBySlug(string $slug): ?Blog
    {
        return Blog::published()
            ->with('blogCategory')
            ->where('slug', $slug)
            ->first();
    }

    /**
     * Get blog post by slug only (including unpublished).
     */
    public function getPostBySlugWithUnpublished(string $slug): ?Blog
    {
        return Blog::with('blogCategory')
            ->where('slug', $slug)
            ->first();
    }

    /**
     * Get related posts.
     */
    public function getRelatedPosts(Blog $post, int $limit = 3): Collection
    {
        $relatedPosts = Blog::published()
            ->with('blogCategory')
            ->where('id', '!=', $post->id)
            ->where('category_id', $post->category_id)
            ->ordered()
            ->limit($limit)
            ->get();

        // If not enough related posts in same category, add from other categories
        if ($relatedPosts->count() < $limit) {
            $additionalPosts = Blog::published()
                ->with('blogCategory')
                ->where('id', '!=', $post->id)
                ->whereNotIn('id', $relatedPosts->pluck('id'))
                ->ordered()
                ->limit($limit - $relatedPosts->count())
                ->get();

            $relatedPosts = $relatedPosts->merge($additionalPosts);
        }

        return $relatedPosts;
    }
}
