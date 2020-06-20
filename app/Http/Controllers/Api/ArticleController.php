<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleCollection;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index()
    {
       // dd(request());
        $articles = Article::applySorts(request('sort'))
            ->jsonPaginate();

        return ArticleResource::collection($articles);
    }

    public function show(Article $article)
    {
        return ArticleResource::make($article);
    }
}
