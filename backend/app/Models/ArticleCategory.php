<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ArticleCategory extends Model
{
    use HasUuids;

    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug)) {
                $baseSlug = Str::slug($category->name);
                $slug = $baseSlug;
                $count = 1;

                while (ArticleCategory::where('slug', $slug)->exists()) {
                    $slug = $baseSlug.'-'.$count++;
                }

                $category->slug = $slug;
            }
        });

        static::updating(function ($category) {
            if ($category->isDirty('name')) {
                $baseSlug = Str::slug($category->name);
                $slug = $baseSlug;
                $count = 1;

                while (
                    ArticleCategory::where('slug', $slug)
                        ->where('id', '!=', $category->id)
                        ->exists()
                ) {
                    $slug = $baseSlug.'-'.$count++;
                }

                $category->slug = $slug;
            }
        });
    }

    public function articles()
    {
        return $this->hasMany(Article::class);
    }
}
