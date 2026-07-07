<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;
use App\Models\Blog;
use App\Models\Service;
use App\Models\ProjectCase;
use App\Models\BlogCategory;
use App\Models\IndustryCategory;

class SitemapController extends Controller
{
    public function index()
    {
        $urls = [];

        // Static pages
        $urls[] = [
            'loc' => URL::to('/'),
            'lastmod' => Carbon::now()->toAtomString(),
            'changefreq' => 'daily',
            'priority' => '1.0'
        ];

        $urls[] = [
            'loc' => URL::to('/contacts'),
            'lastmod' => Carbon::now()->toAtomString(),
            'changefreq' => 'monthly',
            'priority' => '0.8'
        ];

        $urls[] = [
            'loc' => URL::to('/services'),
            'lastmod' => Carbon::now()->toAtomString(),
            'changefreq' => 'weekly',
            'priority' => '0.9'
        ];

        $urls[] = [
            'loc' => URL::to('/cases'),
            'lastmod' => Carbon::now()->toAtomString(),
            'changefreq' => 'weekly',
            'priority' => '0.9'
        ];

        $urls[] = [
            'loc' => URL::to('/blog'),
            'lastmod' => Carbon::now()->toAtomString(),
            'changefreq' => 'daily',
            'priority' => '0.9'
        ];

        // Published services
        $services = Service::published()->get();
        foreach ($services as $service) {
            $urls[] = [
                'loc' => URL::to('/services/' . $service->slug),
                'lastmod' => $service->updated_at->toAtomString(),
                'changefreq' => 'monthly',
                'priority' => '0.8'
            ];
        }

        // Published blog posts
        $blogs = Blog::published()->get();
        foreach ($blogs as $blog) {
            $urls[] = [
                'loc' => $blog->url,
                'lastmod' => $blog->updated_at->toAtomString(),
                'changefreq' => 'weekly',
                'priority' => '0.7'
            ];
        }

        // Published project cases
        $cases = ProjectCase::published()->get();
        foreach ($cases as $case) {
            $urls[] = [
                'loc' => URL::to('/cases/' . $case->case_id),
                'lastmod' => $case->updated_at->toAtomString(),
                'changefreq' => 'monthly',
                'priority' => '0.6'
            ];
        }

        // Blog categories
        $blogCategories = BlogCategory::where('is_active', true)->get();
        foreach ($blogCategories as $category) {
            $urls[] = [
                'loc' => URL::to('/blog/category/' . $category->slug),
                'lastmod' => Carbon::now()->toAtomString(),
                'changefreq' => 'weekly',
                'priority' => '0.6'
            ];
        }

        // Industry categories
        $industryCategories = IndustryCategory::where('is_active', true)->get();
        foreach ($industryCategories as $category) {
            $urls[] = [
                'loc' => URL::to('/cases/category/' . $category->slug),
                'lastmod' => Carbon::now()->toAtomString(),
                'changefreq' => 'monthly',
                'priority' => '0.6'
            ];
        }

        $xml = $this->generateSitemap($urls);

        return response($xml, 200)->header('Content-Type', 'application/xml');
    }

    private function generateSitemap($urls)
    {
        $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><urlset/>');
        $xml->addAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');

        foreach ($urls as $url) {
            $urlTag = $xml->addChild('url');
            $urlTag->addChild('loc', htmlspecialchars($url['loc'], ENT_XML1, 'UTF-8'));
            $urlTag->addChild('lastmod', $url['lastmod']);
            $urlTag->addChild('changefreq', $url['changefreq']);
            $urlTag->addChild('priority', $url['priority']);
        }

        return $xml->asXML();
    }
}
