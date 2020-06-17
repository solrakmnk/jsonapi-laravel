<?php

namespace App\Http\Resources;

use App\Models\Article;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ArticleCollection extends ResourceCollection
{
    public $collects = ArticleResource::class;

    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection, // ArticleResource::collection($this->collection)
            'links' => [
                'self' => route('api.v1.articles.index')
            ],
            'meta' => [
                'articles_count' => 3
            ]
        ];
    }
}
