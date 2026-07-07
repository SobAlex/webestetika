<?php

namespace App\Console\Commands;

use App\Models\Service;
use App\Models\Blog;
use App\Models\ProjectCase;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';
    protected $description = 'Generate sitemap.xml file';

    public function handle()
    {
        $this->info('Generating sitemap.xml...');

        $urls = collect();

        // Главная страница
        $urls->push([
            'loc' => route('home'),
            'lastmod' => now()->format('Y-m-d'),
            'changefreq' => 'daily',
            'priority' => '1.0'
        ]);

        // Страница услуг
        $urls->push([
            'loc' => route('services.index'),
            'lastmod' => now()->format('Y-m-d'),
            'changefreq' => 'weekly',
            'priority' => '0.9'
        ]);

        // Страница кейсов
        $urls->push([
            'loc' => route('cases'),
            'lastmod' => now()->format('Y-m-d'),
            'changefreq' => 'weekly',
            'priority' => '0.8'
        ]);

        // Страница блога
        $urls->push([
            'loc' => route('blog'),
            'lastmod' => now()->format('Y-m-d'),
            'changefreq' => 'daily',
            'priority' => '0.8'
        ]);

        // Контакты
        $urls->push([
            'loc' => route('contacts'),
            'lastmod' => now()->format('Y-m-d'),
            'changefreq' => 'monthly',
            'priority' => '0.7'
        ]);

        // Услуги
        Service::published()->get()->each(function ($service) use ($urls) {
            $urls->push([
                'loc' => route('services.show', $service->slug),
                'lastmod' => $service->updated_at->format('Y-m-d'),
                'changefreq' => 'monthly',
                'priority' => '0.8'
            ]);
        });

        // Статьи блога
        Blog::published()->get()->each(function ($article) use ($urls) {
            $urls->push([
                'loc' => $article->url,
                'lastmod' => $article->updated_at->format('Y-m-d'),
                'changefreq' => 'monthly',
                'priority' => '0.7'
            ]);
        });

        // Кейсы
        ProjectCase::published()->get()->each(function ($case) use ($urls) {
            $urls->push([
                'loc' => $case->url,
                'lastmod' => $case->updated_at->format('Y-m-d'),
                'changefreq' => 'monthly',
                'priority' => '0.7'
            ]);
        });

        // Генерируем XML
        $xml = $this->generateXml($urls);

        // Сохраняем файл
        Storage::disk('public')->put('sitemap.xml', $xml);

        $this->info('Sitemap generated successfully!');
        $this->info('Total URLs: ' . $urls->count());
        $this->info('File saved to: storage/app/public/sitemap.xml');
    }

    private function generateXml($urls)
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        foreach ($urls as $url) {
            $xml .= '  <url>' . "\n";
            $xml .= '    <loc>' . htmlspecialchars($url['loc']) . '</loc>' . "\n";
            $xml .= '    <lastmod>' . $url['lastmod'] . '</lastmod>' . "\n";
            $xml .= '    <changefreq>' . $url['changefreq'] . '</changefreq>' . "\n";
            $xml .= '    <priority>' . $url['priority'] . '</priority>' . "\n";
            $xml .= '  </url>' . "\n";
        }

        $xml .= '</urlset>';

        return $xml;
    }
}