<?php

namespace App\JsonApi\Articles;

use App\Models\Article;
use Neomerx\JsonApi\Schema\SchemaProvider;

class Schema extends SchemaProvider
{

    /**
     * @var string
     */
    protected $resourceType = 'articles';

    /**
     * @param Article $resource
     *      the domain record being serialized.
     * @return string
     */
    public function getId($resource)
    {
        return (string)$resource->getRouteKey();
    }

    /**
     * @param Article $resource
     *      the domain record being serialized.
     * @return array
     */
    public function getAttributes($article)
    {
        return [
            'title' => $article->title,
            'slug' => $article->slug,
            'content' => $article->content,
            'created-at' => $article->created_at->toAtomString(),
            'updated-at' => $article->updated_at->toAtomString(),
        ];
    }

    public function getRelationships($article, $isPrimary, array $includeRelationships)
    {
        return [
            'authors' => [
                'data' => function () use ( $article) {
                    return $article->user;
                }
            ]
        ];
    }
}
