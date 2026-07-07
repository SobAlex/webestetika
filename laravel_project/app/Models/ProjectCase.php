<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\HasPublishing;

use App\Contracts\ImageableInterface;
use App\Contracts\PublishableInterface;
use App\Traits\HasImage;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class ProjectCase extends Model implements ImageableInterface, PublishableInterface
{
    use HasFactory, HasImage, HasPublishing;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cases';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'case_id',
        'title',
        'client',
        'industry_category_id',
        'period',
        'image',
        'description',
        'content',
        'meta_title',
        'meta_description',
        'result_1',
        'result_2',
        'result_3',
        'result_4',
        'result_5',
        'result_6',
        'before_after',
        'is_published',
        'sort_order',
        'service_link_text',
        'service_link_url',
        'user_id',
        // Before/After metrics
        'traffic_before', 'traffic_after',
        'keywords_before', 'keywords_after',
        'conversion_before', 'conversion_after',
        'revenue_before', 'revenue_after',
        'appointments_before', 'appointments_after',
        'calls_before', 'calls_after',
        'leads_before', 'leads_after',
        'cost_per_lead_before', 'cost_per_lead_after',
        'mobile_traffic_before', 'mobile_traffic_after',
        'repeat_clients_before', 'repeat_clients_after',
        'enrollments_before', 'enrollments_after',
        'time_on_site_before', 'time_on_site_after',
        'local_traffic_before', 'local_traffic_after',
        'map_views_before', 'map_views_after',
        'reservations_before', 'reservations_after',
        'avg_check_before', 'avg_check_after',
        'b2b_traffic_before', 'b2b_traffic_after',
        'large_orders_before', 'large_orders_after',
        'avg_project_before', 'avg_project_after',
        'orders_before', 'orders_after',
        'inquiries_before', 'inquiries_after',
        'sales_before', 'sales_after',
        'cost_per_sale_before', 'cost_per_sale_after',
        'avg_order_before', 'avg_order_after',
        'catalog_conversion_before', 'catalog_conversion_after',
        'brand_traffic_before', 'brand_traffic_after',
        'product_views_before', 'product_views_after',
        // Новые поля для динамических блоков
        'metrics',
        'results',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'before_after' => 'array',
        'is_published' => 'boolean',
        'image' => 'string',
        'metrics' => 'array',
        'results' => 'array',
    ];

        // скопы реализованы в трейте

        // методы ниже пока не разобраны. Разобраться, где применяются. Не нужные удалить.


    /**
     * Get the user that owns the case.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the industry category for this case.
     */
    public function industryCategory(): BelongsTo
    {
        return $this->belongsTo(IndustryCategory::class, 'industry_category_id');
    }

    /**
     * Scope a query to filter by industry.
     */
    public function scopeByIndustry($query, $industry)
    {
        return $query->whereHas('industryCategory', function($query) use ($industry) {
            $query->where('slug', $industry);
        });
    }


    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'case_id';
    }

    /**
     * Check if the case is published.
     */
    public function isPublished(): bool
    {
        return $this->is_published;
    }

    /**
     * Check if the case is active.
     */
    public function isActive(): bool
    {
        return $this->is_published;
    }

    /**
     * Get the image attribute.
     */
    public function getImageAttribute(): ?string
    {
        $image = $this->attributes['image'] ?? null;

        // Безопасная обработка для предотвращения "undefined"
        if (is_string($image) && !empty(trim($image))) {
            return trim($image);
        }

        return null;
    }

    /**
     * Get the case's excerpt.
     */
    public function getExcerptAttribute()
    {
        return Str::limit(strip_tags($this->description), 120);
    }

    /**
     * Get the case's URL.
     */
    public function getUrlAttribute()
    {
        return route('cases.show', $this->case_id);
    }


    /**
     * Get industry name in Russian.
     */
    public function getIndustryNameAttribute()
    {
        return $this->industryCategory ? $this->industryCategory->name : 'Без категории';
    }

    /**
     * Get active industry name in Russian.
     */
    public function getActiveIndustryNameAttribute()
    {
        return $this->activeIndustryCategory ? $this->activeIndustryCategory->name : null;
    }

    /**
     * Get the active industry category that owns the case.
     */
    public function activeIndustryCategory(): BelongsTo
    {
        return $this->belongsTo(IndustryCategory::class, 'industry_category_id')->where('is_active', true);
    }

    /**
     * Check if the case has an active industry category.
     */
    public function hasActiveIndustryCategory(): bool
    {
        return $this->activeIndustryCategory !== null;
    }

    /**
     * Scope a query to only include cases with active industry categories.
     */
    public function scopeWithActiveIndustryCategories($query)
    {
        return $query->whereHas('industryCategory', function($query) {
            $query->where('is_active', true);
        });
    }

    /**
     * Get results as array from new dynamic field or individual fields for backward compatibility.
     */
    public function getResultsArrayAttribute()
    {
        // Если есть новые динамические результаты, используем их
        if (!empty($this->results) && is_array($this->results)) {
            // Проверяем структуру данных: если это простой массив строк, возвращаем как есть
            if (isset($this->results[0]) && is_string($this->results[0])) {
                return array_filter($this->results);
            }
            // Если это массив объектов с ключом 'result', извлекаем значения
            return collect($this->results)->pluck('result')->filter()->values()->toArray();
        }

        // Иначе используем старые поля result_1 - result_6 для обратной совместимости
        $results = [];
        for ($i = 1; $i <= 6; $i++) {
            $field = "result_{$i}";
            if (!empty($this->$field)) {
                $results[] = $this->$field;
            }
        }
        return $results;
    }

    /**
     * Set results from array to individual fields.
     */
    public function setResultsArrayAttribute($value)
    {
        if (is_array($value)) {
            for ($i = 1; $i <= 6; $i++) {
                $field = "result_{$i}";
                $this->$field = $value[$i - 1] ?? null;
            }
        }
    }

    /**
     * Get before/after metrics as array for backward compatibility.
     */
    public function getBeforeAfterArrayAttribute()
    {
        $metrics = [];

        $metricMapping = [
            'traffic' => ['traffic_before', 'traffic_after'],
            'keywords' => ['keywords_before', 'keywords_after'],
            'conversion' => ['conversion_before', 'conversion_after'],
            'revenue' => ['revenue_before', 'revenue_after'],
            'appointments' => ['appointments_before', 'appointments_after'],
            'calls' => ['calls_before', 'calls_after'],
            'leads' => ['leads_before', 'leads_after'],
            'cost_per_lead' => ['cost_per_lead_before', 'cost_per_lead_after'],
            'mobile_traffic' => ['mobile_traffic_before', 'mobile_traffic_after'],
            'repeat_clients' => ['repeat_clients_before', 'repeat_clients_after'],
            'enrollments' => ['enrollments_before', 'enrollments_after'],
            'time_on_site' => ['time_on_site_before', 'time_on_site_after'],
            'local_traffic' => ['local_traffic_before', 'local_traffic_after'],
            'map_views' => ['map_views_before', 'map_views_after'],
            'reservations' => ['reservations_before', 'reservations_after'],
            'avg_check' => ['avg_check_before', 'avg_check_after'],
            'b2b_traffic' => ['b2b_traffic_before', 'b2b_traffic_after'],
            'large_orders' => ['large_orders_before', 'large_orders_after'],
            'avg_project' => ['avg_project_before', 'avg_project_after'],
            'orders' => ['orders_before', 'orders_after'],
            'inquiries' => ['inquiries_before', 'inquiries_after'],
            'sales' => ['sales_before', 'sales_after'],
            'cost_per_sale' => ['cost_per_sale_before', 'cost_per_sale_after'],
            'avg_order' => ['avg_order_before', 'avg_order_after'],
            'catalog_conversion' => ['catalog_conversion_before', 'catalog_conversion_after'],
            'brand_traffic' => ['brand_traffic_before', 'brand_traffic_after'],
            'product_views' => ['product_views_before', 'product_views_after'],
        ];

        foreach ($metricMapping as $metric => $fields) {
            $beforeValue = $this->{$fields[0]};
            $afterValue = $this->{$fields[1]};

            if ($beforeValue || $afterValue) {
                $metrics[$metric] = [
                    'before' => $beforeValue,
                    'after' => $afterValue,
                ];
            }
        }

        return $metrics;
    }

    /**
     * Get available metrics for this case from new dynamic field or old fields for backward compatibility.
     */
    public function getAvailableMetricsAttribute()
    {
        // Если есть новые динамические метрики, используем их
        if (!empty($this->metrics) && is_array($this->metrics)) {
            $metrics = [];
            foreach ($this->metrics as $index => $metric) {
                if (!empty($metric['name'])) {
                    $key = 'metric_' . $index;
                    $metrics[$key] = [
                        'before' => $metric['before'] ?? '',
                        'after' => $metric['after'] ?? '',
                        'label' => $metric['name'],
                    ];
                }
            }
            return $metrics;
        }

        // Иначе используем старые поля для обратной совместимости
        $metrics = [];

        $metricMapping = [
            'traffic' => ['traffic_before', 'traffic_after', 'Трафик'],
            'keywords' => ['keywords_before', 'keywords_after', 'Ключевые слова'],
            'conversion' => ['conversion_before', 'conversion_after', 'Конверсия'],
            'revenue' => ['revenue_before', 'revenue_after', 'Выручка'],
            'appointments' => ['appointments_before', 'appointments_after', 'Записи'],
            'calls' => ['calls_before', 'calls_after', 'Звонки'],
            'leads' => ['leads_before', 'leads_after', 'Лиды'],
            'cost_per_lead' => ['cost_per_lead_before', 'cost_per_lead_after', 'Цена лида'],
            'mobile_traffic' => ['mobile_traffic_before', 'mobile_traffic_after', 'Мобильный трафик'],
            'repeat_clients' => ['repeat_clients_before', 'repeat_clients_after', 'Повторные клиенты'],
            'enrollments' => ['enrollments_before', 'enrollments_after', 'Записи на курсы'],
            'time_on_site' => ['time_on_site_before', 'time_on_site_after', 'Время на сайте'],
            'local_traffic' => ['local_traffic_before', 'local_traffic_after', 'Локальный трафик'],
            'map_views' => ['map_views_before', 'map_views_after', 'Просмотры на карте'],
            'reservations' => ['reservations_before', 'reservations_after', 'Бронирования'],
            'avg_check' => ['avg_check_before', 'avg_check_after', 'Средний чек'],
            'b2b_traffic' => ['b2b_traffic_before', 'b2b_traffic_after', 'B2B трафик'],
            'large_orders' => ['large_orders_before', 'large_orders_after', 'Крупные заказы'],
            'avg_project' => ['avg_project_before', 'avg_project_after', 'Средний проект'],
            'orders' => ['orders_before', 'orders_after', 'Заказы'],
            'inquiries' => ['inquiries_before', 'inquiries_after', 'Запросы'],
            'sales' => ['sales_before', 'sales_after', 'Продажи'],
            'cost_per_sale' => ['cost_per_sale_before', 'cost_per_sale_after', 'Цена продажи'],
            'avg_order' => ['avg_order_before', 'avg_order_after', 'Средний заказ'],
            'catalog_conversion' => ['catalog_conversion_before', 'catalog_conversion_after', 'Конверсия каталога'],
            'brand_traffic' => ['brand_traffic_before', 'brand_traffic_after', 'Брендовый трафик'],
            'product_views' => ['product_views_before', 'product_views_after', 'Просмотры товаров'],
        ];

        foreach ($metricMapping as $metric => $data) {
            $beforeValue = $this->{$data[0]};
            $afterValue = $this->{$data[1]};

            if ($beforeValue || $afterValue) {
                $metrics[$metric] = [
                    'before' => $beforeValue,
                    'after' => $afterValue,
                    'label' => $data[2],
                ];
            }
        }

        return $metrics;
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($case) {
            if (empty($case->case_id)) {
                $case->case_id = 'case-' . Str::slug($case->title) . '-' . time();
            }
            // Ensure content is never null
            if (is_null($case->content)) {
                $case->content = '';
            }
        });

        static::updating(function ($case) {
            \Illuminate\Support\Facades\Log::info('Updating case', [
                'id' => $case->id,
                'case_id' => $case->case_id,
                'meta_title' => $case->meta_title,
                'meta_description' => $case->meta_description,
                'dirty' => $case->getDirty()
            ]);
        });
    }

}
