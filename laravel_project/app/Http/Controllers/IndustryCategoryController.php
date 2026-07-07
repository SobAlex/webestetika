<?php

namespace App\Http\Controllers;

use App\Models\IndustryCategory;
use App\Services\CaseService;
use App\Http\Requests\IndustryCategoryRequest;
use Illuminate\Http\Request;

class IndustryCategoryController extends Controller
{
    public function __construct(
        private CaseService $caseService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = IndustryCategory::orderBy('sort_order')->get();
        return response()->json($categories);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(IndustryCategoryRequest $request)
    {
        $validated = $request->validated();

        $industryCategory = IndustryCategory::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Категория индустрии успешно создана.',
            'data' => $industryCategory
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(IndustryCategory $industryCategory)
    {
        return response()->json($industryCategory);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(IndustryCategoryRequest $request, IndustryCategory $industryCategory)
    {
        $validated = $request->validated();

        $industryCategory->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Категория индустрии успешно обновлена.',
            'data' => $industryCategory
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(IndustryCategory $industryCategory)
    {
        $industryCategory->delete();

        return response()->json([
            'success' => true,
            'message' => 'Категория индустрии успешно удалена.'
        ]);
    }

    // ============================================================================
    // API METHODS
    // ============================================================================

    /**
     * Get active industry categories for API.
     */
    public function getActive()
    {
        $categories = $this->caseService->getActiveCategories();
        return response()->json($categories);
    }

    /**
     * Get categories for cases index page.
     */
    public function getForCasesIndex()
    {
        return $this->caseService->getActiveCategories();
    }
}
