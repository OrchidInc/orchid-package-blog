<?php

namespace OrchidInc\Orchid\Blog\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OrchidInc\Status\Classes\StatusHelper;
use Orchid\Attachment\Attachable;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class Category extends Model
{
    use AsSource, Attachable, Filterable;

    protected $table = 'blog_categories';

    protected $fillable = [
        'name',
        'slug',
        'keywords',
        'tags',
        'description',
        'status',
        'image_id',
    ];

    protected function casts(): array
    {
        return [
            'tags' => 'array',
            'image_id' => 'integer',
        ];
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'category_id');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query
            ->where('status', StatusHelper::ACTIVE('base')->id);
    }
}
