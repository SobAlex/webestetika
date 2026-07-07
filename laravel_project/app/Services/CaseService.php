<?php

namespace App\Services;

use App\Models\ProjectCase;
use App\Models\IndustryCategory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;

class CaseService
{
    /**
     * Get latest cases for homepage.
     */
    public function getLatestCasesTransformedForHomepage(int $limit = 4): array
    {
        $cases = ProjectCase::published()
            ->with('industryCategory')
            ->ordered()
            ->limit($limit)
            ->get();

        return $this->transformCasesForTemplate($cases);
    }

    /**
     * Transform case for template.
     */
    public function transformCaseForTemplate(ProjectCase $case): array
    {
        $industrySlug = $case->industryCategory ? $case->industryCategory->slug : 'uncategorized';

        return [
            'id' => $case->case_id,
            'title' => $case->title,
            'client' => $case->client,
            'industry' => $industrySlug,
            'industry_name' => $case->industryCategory && $case->industryCategory->is_active ? $case->industryCategory->name : null,
            'period' => $case->period,
            'image' => $case->image,
            'image_url' => $case->image_url,
            'description' => $case->description,
            'description_clean' => strip_tags(html_entity_decode($case->description)),
            'content' => $case->content,
            'meta_title' => $case->meta_title,
            'meta_description' => $case->meta_description,
            'results' => $case->results_array,
            'before_after' => $case->available_metrics,
            'has_valid_category' => $case->industryCategory ? $case->industryCategory->is_active : false
        ];
    }

    /**
     * Transform cases collection for template.
     */
    public function transformCasesForTemplate(Collection $cases): array
    {
        return $cases->map(function ($case) {
            return $this->transformCaseForTemplate($case);
        })->toArray();
    }

    /**
     * Get active industry categories.
     */
    public function getActiveCategories(): SupportCollection
    {
        return IndustryCategory::active()
            ->ordered()
            ->get()
            ->map(function ($category) {
                return [
                    'name' => $category->name,
                    'route' => 'cases.category',
                    'route_params' => [$category->slug],
                    'slug' => $category->slug,
                    'icon' => $category->icon ?: 'business',
                    'color' => $category->color ?: '#3B82F6',
                    'description' => $category->description
                ];
            });
    }

    // ниже пока не разобранные методы

    /**
     * Get all published cases with their categories.
     */
    public function getAllCases(): Collection
    {
        return ProjectCase::published()
            ->with('industryCategory')
            ->ordered()
            ->get();
    }

    /**
     * Get cases by industry category.
     */
    public function getCasesByIndustry(string $industrySlug): Collection
    {
        return ProjectCase::published()
            ->with('industryCategory')
            ->whereHas('industryCategory', function($query) use ($industrySlug) {
                $query->where('slug', $industrySlug)->where('is_active', true);
            })
            ->ordered()
            ->get();
    }

    /**
     * Get case by case_id (published only).
     */
    public function getCaseById(string $caseId): ?ProjectCase
    {
        return ProjectCase::where('case_id', $caseId)
            ->with('industryCategory')
            ->published()
            ->first();
    }

    /**
     * Get case by case_id (including unpublished).
     */
    public function getCaseByIdWithUnpublished(string $caseId): ?ProjectCase
    {
        return ProjectCase::where('case_id', $caseId)
            ->with('industryCategory')
            ->first();
    }

    /**
     * Get category info by slug.
     */
    public function getCategoryInfo(string $industrySlug): ?array
    {
        $category = IndustryCategory::where('slug', $industrySlug)->first();

        if ($category && $category->is_active) {
            return [
                'name' => $category->name,
                'route' => 'cases.category',
                'route_params' => [$category->slug],
                'slug' => $category->slug
            ];
        }

        // Fallback to legacy categories
        return $this->getLegacyCategoryInfo($industrySlug);
    }

    /**
     * Get legacy category info.
     */
    private function getLegacyCategoryInfo(string $industrySlug): ?array
    {
        $legacyCategories = [
            'clothing' => [
                'name' => 'Одежда',
                'route' => 'cases.clothing',
                'route_params' => []
            ],
            'production' => [
                'name' => 'Производство',
                'route' => 'cases.production',
                'route_params' => []
            ],
            'electronics' => [
                'name' => 'Электроника',
                'route' => 'cases.electronics',
                'route_params' => []
            ],
            'furniture' => [
                'name' => 'Мебель',
                'route' => 'cases.furniture',
                'route_params' => []
            ]
        ];

        return $legacyCategories[$industrySlug] ?? null;
    }
}
