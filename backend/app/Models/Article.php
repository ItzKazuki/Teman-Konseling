<?php

namespace App\Models;

use App\Enums\ArticleStatus;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Article extends Model
{
    use HasUuids;

    protected $fillable = [
        'author_id',
        'article_category_id',
        'title',
        'slug',
        'excerpt',
        'thumbnail_file_id',
        'content',
        'status',
        'views',
        'published_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'status' => ArticleStatus::class,
        ];
    }

    protected $appends = [
        'thumbnail_url',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($article) {
            if (empty($article->slug)) {
                $baseSlug = Str::slug($article->title);
                $slug = $baseSlug;
                $count = 1;

                while (Article::where('slug', $slug)->exists()) {
                    $slug = $baseSlug.'-'.$count++;
                }

                $article->slug = $slug;
            }

            if ($article->status === ArticleStatus::PUBLISHED && ! $article->published_at) {
                $article->published_at = now();
            }
        });

        static::updating(function ($article) {
            if ($article->isDirty('title')) {
                $baseSlug = Str::slug($article->title);
                $slug = $baseSlug;
                $count = 1;

                while (
                    Article::where('slug', $slug)
                        ->where('id', '!=', $article->id)
                        ->exists()
                ) {
                    $slug = $baseSlug.'-'.$count++;
                }

                $article->slug = $slug;
            }
        });
    }

    public function getThumbnailUrlAttribute(): string
    {
        if ($this->thumbnail && Storage::disk('public')->exists($this->thumbnail->file_path)) {
            return Storage::url($this->thumbnail->file_path);
        }

        return asset('static/kesmen.png');
    }

    public function thumbnail(): BelongsTo
    {
        return $this->belongsTo(File::class, 'thumbnail_file_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ArticleCategory::class, 'article_category_id');
    }
}
