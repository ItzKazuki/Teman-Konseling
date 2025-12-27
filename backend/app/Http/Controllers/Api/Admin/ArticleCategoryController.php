<?php

namespace App\Http\Controllers\Api\Admin;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\ArticleCategoryRequest;
use App\Http\Resources\ArticleCategoryResource;
use App\Models\ArticleCategory;
use Illuminate\Http\Request;
use Dedoc\Scramble\Attributes\Group;

#[Group('Admin Master Data: Kategori Artikel', weight: 10)]
class ArticleCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $categories = ArticleCategory::all();

        // return ApiResponse::success(ArticleCategoryResource::collection($categories));

        $perPage = $request->input('perPage', 10);
        $query = ArticleCategory::query();

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('slug', 'like', "%{$search}%");
            });
        }
        
        $categories = $query->paginate($perPage);

        $categories->getCollection()->transform(function ($category) {
            return new ArticleCategoryResource($category);
        });

        return ApiResponse::success($categories, 'Berhasil mengambil data kelas');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ArticleCategoryRequest $request)
    {
        $validatedData = $request->validated();
        
        $category = ArticleCategory::create($validatedData);

        return ApiResponse::success(new ArticleCategoryResource($category), 'Kategori artikel berhasil dibuat.', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = ArticleCategory::find($id);

        if(!$category) {
            return ApiResponse::error('Kategori artikel tidak ditemukan', 404);
        }

        return ApiResponse::success(new ArticleCategoryResource($category));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ArticleCategoryRequest $request, string $id)
    {
        $category = ArticleCategory::find($id);

        if(!$category) {
            return ApiResponse::error('Kategori artikel tidak ditemukan', 404);
        }

        $validatedData = $request->validated();
        
        $category->update($validatedData);

        return ApiResponse::success(null, 'Berhasil mengubah kategori artikel');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = ArticleCategory::find($id);

        if(!$category) {
            return ApiResponse::error('Kategori artikel tidak ditemukan', 404);
        }

        $category->delete();

        return ApiResponse::success(null, 'Berhasil menghapus kategori artikel');
    }
}
