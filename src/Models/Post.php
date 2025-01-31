<?php

namespace OrchidInc\Orchid\Blog\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Orchid\Attachment\Attachable;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class Post extends Model
{
    use AsSource;
    use Attachable;
    use Filterable;

    protected $table = 'blog_posts';

    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'keywords',
        'tags',
        'introductory',
        'content',
        'status',
        'image_id',
        'recommended',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'category_id' => 'integer',
            'tags' => 'array',
            'image_id' => 'integer',
            'recommended' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function scopePublished($query, $date = null)
    {
        return $query->where("{$this->table}.published_at", '<=', $date ?? now());
    }
}
