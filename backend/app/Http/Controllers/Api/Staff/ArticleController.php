<?php

namespace App\Http\Controllers\Api\Staff;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\ArticleRequest;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\Request;

#[Group('Staff: Manajemen Artikel', weight: 4)]
class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->input('perPage', 10);
        $query = Article::query();

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $articles = $query->with(['author', 'category'])->paginate($perPage);

        $articles->getCollection()->transform(function ($article) {
            return new ArticleResource($article);
        });

        return ApiResponse::success($articles, 'Berhasil mengambil data artikel');
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
