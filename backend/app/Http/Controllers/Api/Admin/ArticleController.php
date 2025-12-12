<?php

namespace App\Http\Controllers\Api\Admin;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\ArticleRequest;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use Dedoc\Scramble\Attributes\Group;

#[Group('Admin: Artikel', weight: 3)]
class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $articles = Article::with(['author', 'category'])->get();

        return ApiResponse::success(ArticleResource::collection($articles));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ArticleRequest $request)
    {
        $validatedData = $request->validated();

        $validatedData['author_id'] = $request->user('user')->id;

        $article = Article::create($validatedData);

        return ApiResponse::success(new ArticleResource($article), 'Artikel berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $article = Article::find($id);

        if (! $article) {
            return ApiResponse::error('Artikel tidak ditemukan', 404);
        }

        return ApiResponse::success(new ArticleResource($article->load(['author', 'category'])));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ArticleRequest $request, string $id)
    {
        $article = Article::find($id);

        if (! $article) {
            return ApiResponse::error('Artikel tidak ditemukan', 404);
        }

        $validatedData = $request->validated();

        $article->update($validatedData);

        return ApiResponse::success(null, 'Berhasil mengubah kategori artikel');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $article = Article::find($id);

        if (! $article) {
            return ApiResponse::error('Artikel tidak ditemukan', 404);
        }

        $article->delete();

        return ApiResponse::success(null, 'Berhasil menghapus artikel');
    }
}
