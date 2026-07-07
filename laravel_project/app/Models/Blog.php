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

class Blog extends Model implements ImageableInterface, PublishableInterface
{
    use HasFactory, HasImage, HasPublishing;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'image',
        'category_id',
        'meta_title',
        'meta_description',
        'is_published',
        'sort_order',
        'user_id',
        'published_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
        'image' => 'string',
    ];

    // скопы реализованы в трейте

    // методы ниже пока не разобраны. Разобраться, где применяются. Не нужные удалить.

    /**
     * Get the user that owns the blog post.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the blog category that owns the blog post.
     */
    public function blogCategory(): BelongsTo
    {
        return $this->belongsTo(BlogCategory::class, 'category_id');
    }

    /**
     * Get the blog category alias for backward compatibility.
     */
    public function category(): BelongsTo
    {
        return $this->blogCategory();
    }

    /**
     * Get the active blog category that owns the blog post.
     */
    public function activeBlogCategory(): BelongsTo
    {
        return $this->belongsTo(BlogCategory::class, 'category_id')->where('is_active', true);
    }

    /**
     * Scope a query to filter by category.
     */
    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    /**
     * Check if the blog post is published.
     */
    public function isPublished(): bool
    {
        return $this->is_published;
    }

    /**
     * Check if the blog post is active.
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
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Generate slug from title.
     */
    public function generateSlug()
    {
        $this->slug = Str::slug($this->title);
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($blog) {
            if (empty($blog->slug) && !empty($blog->title)) {
                $blog->slug = Str::slug($blog->title);
            }
            // Ensure content is never null
            if (is_null($blog->content)) {
                $blog->content = '';
            }
            // Set published_at when publishing
            if ($blog->is_published && empty($blog->published_at)) {
                $blog->published_at = now();
            }
        });

        static::updating(function ($blog) {
            if (empty($blog->slug) && !empty($blog->title)) {
                $blog->slug = Str::slug($blog->title);
            }
            // Set published_at when publishing for the first time
            if ($blog->is_published && empty($blog->published_at)) {
                $blog->published_at = now();
            }
        });
    }

    /**
     * Get the category name from related BlogCategory.
     */
    public function getCategoryNameAttribute()
    {
        return $this->blogCategory ? $this->blogCategory->name : 'Без категории';
    }

    /**
     * Get the active category name from related BlogCategory.
     */
    public function getActiveCategoryNameAttribute()
    {
        return $this->activeBlogCategory ? $this->activeBlogCategory->name : null;
    }

    /**
     * Check if the blog has an active category.
     */
    public function hasActiveCategory(): bool
    {
        return $this->activeBlogCategory !== null;
    }

    /**
     * Scope a query to only include blogs with active categories.
     */
    public function scopeWithActiveCategories($query)
    {
        return $query->whereHas('blogCategory', function($query) {
            $query->where('is_active', true);
        });
    }

    /**
     * Get the formatted published date.
     */
    public function getFormattedPublishedAtAttribute()
    {
        return $this->published_at ? $this->published_at->format('d.m.Y H:i') : 'Не опубликовано';
    }

    /**
     * Get the excerpt attribute with HTML tags removed.
     */
    public function getExcerptAttribute()
    {
        return strip_tags(html_entity_decode($this->attributes['excerpt'] ?? ''));
    }

    /**
     * Get the URL for the blog post.
     */
    public function getUrlAttribute(): string
    {
        if ($this->hasActiveCategory()) {
            return route('blog.article', [
                'category' => $this->blogCategory->slug,
                'slug' => $this->slug
            ]);
        }

        return route('blog.article.uncategorized', ['slug' => $this->slug]);
    }

}
