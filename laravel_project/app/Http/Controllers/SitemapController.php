<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\Contact;
use App\Models\IndustryCategory;
use App\Models\ProjectCase;
use App\Models\Service;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $services = Service::published()->get();
        $blogs = Blog::published()->with('activeBlogCategory')->get();
        $cases = ProjectCase::published()->get();
        $blogCategories = BlogCategory::query()->where('is_active', true)->orderBy('sort_order')->get();
        $industryCategories = IndustryCategory::query()->where('is_active', true)->orderBy('sort_order')->get();

        $urls = [];

        $this->addUrl($urls, route('home'), $this->latest(
            $this->contactUpdatedAt(),
            $this->latestOf($services),
            $this->latestOf($blogs),
            $this->latestOf($cases),
        ), 'daily', '1.0');

        $this->addUrl($urls, route('contacts'), $this->contactUpdatedAt(), 'monthly', '0.8');
        $this->addUrl($urls, route('services.index'), $this->latestOf($services), 'weekly', '0.9');
        $this->addUrl($urls, route('cases.index'), $this->latestOf($cases), 'weekly', '0.9');
        $this->addUrl($urls, route('blog.index'), $this->latestOf($blogs), 'daily', '0.9');

        foreach ($services as $service) {
            $this->addUrl($urls, route('services.show', $service), $service->updated_at, 'monthly', '0.8');
        }

        foreach ($blogs as $blog) {
            $this->addUrl($urls, $blog->url, $blog->updated_at, 'weekly', '0.7');
        }

        foreach ($cases as $case) {
            $this->addUrl($urls, route('cases.show', $case->case_id), $case->updated_at, 'monthly', '0.6');
        }

        foreach ($blogCategories as $category) {
            $latestPost = Blog::published()->where('category_id', $category->id)->max('updated_at');

            $this->addUrl(
                $urls,
                route('blog.category', $category->slug),
                $latestPost ? Carbon::parse($latestPost) : $category->updated_at,
                'weekly',
                '0.6',
            );
        }

        foreach ($industryCategories as $category) {
            $latestCase = ProjectCase::published()->where('industry_category_id', $category->id)->max('updated_at');

            $this->addUrl(
                $urls,
                route('cases.category', $category->slug),
                $latestCase ? Carbon::parse($latestCase) : $category->updated_at,
                'monthly',
                '0.6',
            );
        }

        $this->addUrl($urls, route('policy'), $this->viewUpdatedAt('pages/policy.blade.php'), 'yearly', '0.3');
        $this->addUrl($urls, route('soglasie'), $this->viewUpdatedAt('pages/soglasie.blade.php'), 'yearly', '0.3');
        $this->addUrl($urls, route('terms'), $this->viewUpdatedAt('pages/terms.blade.php'), 'yearly', '0.3');

        return response($this->xml($urls), 200)->header('Content-Type', 'application/xml; charset=UTF-8');
    }

    /**
     * @param  array<int, array{loc: string, lastmod?: string, changefreq: string, priority: string}>  $urls
     */
    private function addUrl(array &$urls, string $loc, ?CarbonInterface $lastmod, string $changefreq, string $priority): void
    {
        $url = [
            'loc' => $loc,
            'changefreq' => $changefreq,
            'priority' => $priority,
        ];

        if ($lastmod) {
            $url['lastmod'] = $lastmod->toAtomString();
        }

        $urls[] = $url;
    }

    private function contactUpdatedAt(): ?Carbon
    {
        $updatedAt = Contact::query()->max('updated_at');

        return $updatedAt ? Carbon::parse($updatedAt) : null;
    }

    /**
     * @param  iterable<int, object>  $models
     */
    private function latestOf(iterable $models): ?CarbonInterface
    {
        $latest = null;

        foreach ($models as $model) {
            $updatedAt = $model->updated_at ?? null;

            if ($updatedAt && ($latest === null || $updatedAt->gt($latest))) {
                $latest = $updatedAt;
            }
        }

        return $latest;
    }

    private function latest(?CarbonInterface ...$dates): ?CarbonInterface
    {
        $latest = null;

        foreach ($dates as $date) {
            if ($date && ($latest === null || $date->gt($latest))) {
                $latest = $date;
            }
        }

        return $latest;
    }

    private function viewUpdatedAt(string $view): ?Carbon
    {
        $path = resource_path('views/'.$view);
        $time = is_file($path) ? filemtime($path) : false;

        return $time ? Carbon::createFromTimestamp($time) : null;
    }

    /**
     * @param  array<int, array{loc: string, lastmod?: string, changefreq: string, priority: string}>  $urls
     */
    private function xml(array $urls): string
    {
        $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><urlset/>');
        $xml->addAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');

        foreach ($urls as $url) {
            $urlTag = $xml->addChild('url');
            $urlTag->addChild('loc', $url['loc']);

            if (isset($url['lastmod'])) {
                $urlTag->addChild('lastmod', $url['lastmod']);
            }

            $urlTag->addChild('changefreq', $url['changefreq']);
            $urlTag->addChild('priority', $url['priority']);
        }

        return (string) $xml->asXML();
    }
}
