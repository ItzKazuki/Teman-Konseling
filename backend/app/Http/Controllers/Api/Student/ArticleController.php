<?php

namespace App\Http\Controllers\Api\Student;

use App\Enums\ArticleStatus;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Support\Facades\Cache;

#[Group('Student: Article', weight: 3)]
class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::where('status', ArticleStatus::PUBLISHED)->with('author', 'category', 'thumbnail')->get();

        return ApiResponse::success(ArticleResource::collection($articles));
    }

    public function show(string $slug)
    {
        $article = Article::where('slug', $slug)->where('status', ArticleStatus::PUBLISHED)->with('author', 'category', 'thumbnail')->first();

        if (! $article) {
            return ApiResponse::error('Artikel tidak ditemukan', 404);
        }

        $cacheKey = 'viewed_article_'.$article->id.'_'.request()->ip();

        if (! Cache::has($cacheKey)) {
            $article->increment('views');

            Cache::put($cacheKey, true, 3600);
        }

        return ApiResponse::success(new ArticleResource($article));
    }
}
