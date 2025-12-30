<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'author_id' => $this->author_id,
            'article_category_id' => $this->article_category_id,
            'thumbnail_file_id' => $this->thumbnail_file_id,
            'thumbnail_url' => $this->thumbnail_url,
            'author_name' => $this->whenLoaded('author', fn () => $this->author->name),
            'category_name' => $this->whenLoaded('category', fn () => $this->category->name),
            'title' => $this->title,
            'slug' => $this->slug,
            'excerpt' => $this->excerpt,
            'content' => $this->content,
            'status' => $this->status,
            'views' => $this->views,
            'published_at' => $this->published_at?->toIso8601String(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
