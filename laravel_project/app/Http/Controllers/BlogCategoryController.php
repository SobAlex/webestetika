<?php

namespace App\Http\Controllers;

use App\Models\BlogCategory;
use App\Services\BlogService;
use App\Http\Requests\BlogCategoryRequest;
use Illuminate\Http\Request;

class BlogCategoryController extends Controller
{
    public function __construct(
        private BlogService $blogService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = BlogCategory::orderBy('sort_order')->get();
        return response()->json($categories);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BlogCategoryRequest $request)
    {
        $validated = $request->validated();

        $blogCategory = BlogCategory::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Категория блога успешно создана.',
            'data' => $blogCategory
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(BlogCategory $blogCategory)
    {
        return response()->json($blogCategory);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BlogCategoryRequest $request, BlogCategory $blogCategory)
    {
        $validated = $request->validated();

        $blogCategory->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Категория блога успешно обновлена.',
            'data' => $blogCategory
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BlogCategory $blogCategory)
    {
        $blogCategory->delete();

        return response()->json([
            'success' => true,
            'message' => 'Категория блога успешно удалена.'
        ]);
    }

    // ============================================================================
    // API METHODS
    // ============================================================================

    /**
     * Get active blog categories for API.
     */
    public function getActive()
    {
        $categories = $this->blogService->getActiveCategories();
        return response()->json($categories);
    }

    /**
     * Get categories for blog index page.
     */
    // public function getForBlogIndex()
    // {
    //     return $this->blogService->getActiveCategories();
    // }
}
